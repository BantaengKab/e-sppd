<?php

namespace App\Http\Controllers;

use App\Models\SPT;
use App\Models\SPPD;
use App\Models\EstimatedCost;
use App\Models\Approval;
use App\Services\SPPDPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SPTWebController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $spts = SPT::with(['user', 'estimatedCosts', 'approvals', 'sppd'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $spts = SPT::where('user_id', $user->id)
                ->with(['estimatedCosts', 'approvals', 'sppd'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('spts.index', compact('spts'));
    }

    public function create()
    {
        return view('spts.create');
    }

    public function show(Request $request, SPT $spt)
    {
        $user = $request->user();

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $spt->load(['user', 'estimatedCosts', 'approvals.approver', 'sppd']);

        return view('spts.show', compact('spt'));
    }

    public function edit(Request $request, SPT $spt)
    {
        $user = $request->user();

        // Only allow editing if draft status
        if ($spt->status !== 'draft') {
            abort(403, 'Cannot edit submitted SPT');
        }

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $spt->load(['estimatedCosts']);

        return view('spts.edit', compact('spt'));
    }

    public function showSppd(Request $request, SPPD $sppd)
    {
        $user = $request->user();
        $spt = $sppd->spt;

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $sppd->load(['spt.user', 'spt.estimatedCosts', 'realizations']);

        return view('sppds.show', compact('sppd'));
    }

    public function downloadPdf(Request $request, SPPD $sppd)
    {
        $user = $request->user();
        $spt = $sppd->spt;

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $pdfService = new SPPDPdfService();
        $pdfContent = $pdfService->generateSPPD($sppd);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="SPPD-' . $sppd->number . '.pdf"',
        ]);
    }

    public function approvals(Request $request)
    {
        $user = $request->user();

        $spts = SPT::where('status', 'submitted')
            ->with(['user', 'estimatedCosts', 'approvals'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('approvals.index', compact('spts'));
    }

    public function approveSpt(Request $request, SPT $spt)
    {
        $user = $request->user();

        if ($spt->status !== 'submitted') {
            abort(403, 'SPT is not in submitted status');
        }

        $spt->load(['user', 'estimatedCosts', 'approvals.approver', 'sppd']);

        return view('approvals.show', compact('spt'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'required|string',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'cost_types.*' => 'required|string|in:transport,daily,accommodation,other',
            'cost_amounts.*' => 'required|numeric|min:0',
            'cost_descriptions.*' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $spt = SPT::create([
                'user_id' => $request->user()->id,
                'title' => $validated['title'],
                'purpose' => $validated['purpose'],
                'destination' => $validated['destination'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
            ]);

            // Save estimated costs
            foreach ($validated['cost_types'] as $index => $type) {
                EstimatedCost::create([
                    'spt_id' => $spt->id,
                    'type' => $type,
                    'amount' => $validated['cost_amounts'][$index],
                    'description' => $validated['cost_descriptions'][$index] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('spts.show', $spt->id)
                ->with('success', 'SPT berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating SPT: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat SPT. Silakan coba lagi.');
        }
    }

    public function update(Request $request, SPT $spt)
    {
        $user = $request->user();

        // Only allow editing if draft status
        if ($spt->status !== 'draft') {
            abort(403, 'Cannot edit submitted SPT');
        }

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'purpose' => 'required|string',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'cost_types.*' => 'required|string|in:transport,daily,accommodation,other',
            'cost_amounts.*' => 'required|numeric|min:0',
            'cost_descriptions.*' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update SPT
            $spt->update([
                'title' => $validated['title'],
                'purpose' => $validated['purpose'],
                'destination' => $validated['destination'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Remove existing costs and create new ones
            $spt->estimatedCosts()->delete();

            foreach ($validated['cost_types'] as $index => $type) {
                EstimatedCost::create([
                    'spt_id' => $spt->id,
                    'type' => $type,
                    'amount' => $validated['cost_amounts'][$index],
                    'description' => $validated['cost_descriptions'][$index] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('spts.show', $spt->id)
                ->with('success', 'SPT berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating SPT: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui SPT. Silakan coba lagi.');
        }
    }

    public function submit(Request $request, SPT $spt)
    {
        $user = $request->user();

        // Only allow submission if draft status
        if ($spt->status !== 'draft') {
            abort(403, 'Cannot submit SPT that is not in draft status');
        }

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Update SPT status
            $spt->update(['status' => 'submitted']);

            // Create initial approval record for supervisor
            Approval::create([
                'spt_id' => $spt->id,
                'approved_by' => null, // Will be assigned when supervisor approves
                'stage' => 'supervisor',
                'status' => 'pending',
                'comment' => null,
                'approved_at' => null,
            ]);

            DB::commit();

            return redirect()->route('spts.show', $spt->id)
                ->with('success', 'SPT berhasil diajukan untuk persetujuan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting SPT: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengajukan SPT. Silakan coba lagi.');
        }
    }

    public function destroy(Request $request, SPT $spt)
    {
        $user = $request->user();

        // Only allow deletion if draft status
        if ($spt->status !== 'draft') {
            abort(403, 'Cannot delete submitted SPT');
        }

        // Check authorization
        if (!$user->isAdmin() && $spt->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            // Delete related records
            $spt->estimatedCosts()->delete();
            $spt->approvals()->delete();

            // Delete SPT
            $spt->delete();

            DB::commit();

            return redirect()->route('spts.index')
                ->with('success', 'SPT berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting SPT: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus SPT. Silakan coba lagi.');
        }
    }
}
