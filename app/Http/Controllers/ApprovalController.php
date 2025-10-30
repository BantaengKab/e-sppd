<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use App\Models\Approval;
use App\Models\SPPD;
use App\Services\SPPDPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Only allow access to approval roles
        if (!$user->hasRole(['supervisor', 'finance', 'verifikator', 'admin'])) {
            abort(403, 'Unauthorized access.');
        }

        $spts = SPT::where('status', 'submitted')
            ->with(['user', 'estimatedCosts', 'approvals'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('approvals.index', compact('spts'));
    }

    public function show(Request $request, SPT $spt)
    {
        $user = $request->user();

        // Only allow access to approval roles
        if (!$user->hasRole(['supervisor', 'finance', 'verifikator', 'admin'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($spt->status !== 'submitted') {
            abort(403, 'SPT is not in submitted status');
        }

        $spt->load(['user', 'estimatedCosts', 'approvals.approver', 'sppd']);

        return view('approvals.show', compact('spt'));
    }

    public function approve(Request $request, SPT $spt)
    {
        $user = $request->user();

        // Only allow access to approval roles
        if (!$user->hasRole(['supervisor', 'finance', 'verifikator', 'admin'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($spt->status !== 'submitted') {
            abort(403, 'SPT is not in submitted status');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'comment' => 'required_if:status,rejected|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Determine current stage and user role
            $currentStage = $this->determineCurrentStage($spt, $user);

            if (!$currentStage) {
                abort(403, 'You are not authorized to approve this SPT at this stage.');
            }

            // Update or create approval record
            $approval = Approval::updateOrCreate(
                [
                    'spt_id' => $spt->id,
                    'stage' => $currentStage,
                ],
                [
                    'approved_by' => $user->id,
                    'status' => $validated['status'],
                    'comment' => $validated['comment'] ?? null,
                    'approved_at' => now(),
                ]
            );

            // Check if all approvals are completed
            if ($validated['status'] === 'approved') {
                $this->processApproval($spt, $currentStage);
            } else {
                // If rejected, update SPT status
                $spt->update(['status' => 'rejected']);
            }

            DB::commit();

            $message = $validated['status'] === 'approved'
                ? 'SPT berhasil disetujui!'
                : 'SPT ditolak dengan catatan.';

            return redirect()->route('approvals.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing approval: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses persetujuan. Silakan coba lagi.');
        }
    }

    private function determineCurrentStage(SPT $spt, $user)
    {
        // Check user role and determine approval stage
        if ($user->hasRole(['supervisor', 'admin'])) {
            // Check if supervisor approval is pending
            $supervisorApproval = $spt->approvals()->where('stage', 'supervisor')->first();
            if (!$supervisorApproval || $supervisorApproval->status === 'pending') {
                return 'supervisor';
            }
        }

        if ($user->hasRole(['finance', 'verifikator', 'admin'])) {
            // Check if finance approval is pending
            $financeApproval = $spt->approvals()->where('stage', 'finance')->first();
            if (!$financeApproval || $financeApproval->status === 'pending') {
                return 'finance';
            }
        }

        if ($user->hasRole(['admin'])) {
            // Check if final approval is pending
            $finalApproval = $spt->approvals()->where('stage', 'final')->first();
            if (!$finalApproval || $finalApproval->status === 'pending') {
                return 'final';
            }
        }

        return null;
    }

    private function processApproval(SPT $spt, $currentStage)
    {
        switch ($currentStage) {
            case 'supervisor':
                // Create next approval stage for finance
                Approval::create([
                    'spt_id' => $spt->id,
                    'approved_by' => null,
                    'stage' => 'finance',
                    'status' => 'pending',
                    'comment' => null,
                    'approved_at' => null,
                ]);
                break;

            case 'finance':
                // Create next approval stage for final approval
                Approval::create([
                    'spt_id' => $spt->id,
                    'approved_by' => null,
                    'stage' => 'final',
                    'status' => 'pending',
                    'comment' => null,
                    'approved_at' => null,
                ]);
                break;

            case 'final':
                // All approvals completed, generate SPPD
                $this->generateSPPD($spt);
                break;
        }
    }

    private function generateSPPD(SPT $spt)
    {
        try {
            // Generate SPPD number
            $sppdNumber = $this->generateSPPDNumber();

            // Create SPPD record
            $sppd = SPPD::create([
                'spt_id' => $spt->id,
                'number' => $sppdNumber,
                'issue_date' => now(),
                'status' => 'issued',
                'notes' => 'SPPD diterbitkan setelah melalui proses persetujuan lengkap.',
            ]);

            // Update SPT status
            $spt->update(['status' => 'approved']);

            Log::info("SPPD generated successfully: {$sppdNumber} for SPT ID: {$spt->id}");

        } catch (\Exception $e) {
            Log::error('Error generating SPPD: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateSPPDNumber()
    {
        $year = date('Y');
        $month = date('m');

        // Get last SPPD number for this month
        $lastSppd = SPPD::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastSppd) {
            $lastNumber = (int) substr($lastSppd->number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "SPPD/{$newNumber}/E-SPPD/{$month}/{$year}";
    }

    public function history(Request $request)
    {
        $user = $request->user();

        // Only allow access to approval roles
        if (!$user->hasRole(['supervisor', 'finance', 'verifikator', 'admin'])) {
            abort(403, 'Unauthorized access.');
        }

        $approvals = Approval::where('approved_by', $user->id)
            ->with(['spt.user', 'spt.estimatedCosts'])
            ->orderBy('approved_at', 'desc')
            ->paginate(10);

        return view('approvals.history', compact('approvals'));
    }
}