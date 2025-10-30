<?php

namespace App\Http\Controllers;

use App\Models\SPPD;
use App\Models\Realization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RealizationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sppd_id' => 'required|exists:sppds,id',
            'type' => 'required|string|in:transport,daily,accommodation,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get SPPD and check authorization
        $sppd = SPPD::findOrFail($validated['sppd_id']);
        $user = $request->user();

        // Only allow SPPD owner to add realizations
        if ($sppd->spt->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow realizations for issued SPPDs
        if ($sppd->status !== 'issued') {
            abort(403, 'Cannot add realizations to this SPPD.');
        }

        try {
            DB::beginTransaction();

            // Handle file upload
            $filePath = null;
            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $fileName = 'realization_' . time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('realizations', $fileName, 'public');
            }

            // Create realization
            $realization = Realization::create([
                'sppd_id' => $validated['sppd_id'],
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'description' => $validated['description'] ?? null,
                'file_path' => $filePath,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('sppds.show', $sppd->id)
                ->with('success', 'Realisasi biaya berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating realization: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan realisasi. Silakan coba lagi.');
        }
    }

    public function update(Request $request, Realization $realization)
    {
        $user = $request->user();

        // Check authorization
        $sppd = $realization->sppd;
        if ($sppd->spt->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow updates for issued SPPDs
        if ($sppd->status !== 'issued') {
            abort(403, 'Cannot update realizations for this SPPD.');
        }

        $validated = $request->validate([
            'type' => 'required|string|in:transport,daily,accommodation,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload
            if ($request->hasFile('file_path')) {
                // Delete old file
                if ($realization->file_path) {
                    Storage::disk('public')->delete($realization->file_path);
                }

                $file = $request->file('file_path');
                $fileName = 'realization_' . time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('realizations', $fileName, 'public');
                $validated['file_path'] = $filePath;
            } else {
                unset($validated['file_path']);
            }

            // Update realization
            $realization->update($validated);

            DB::commit();

            return redirect()->route('sppds.show', $sppd->id)
                ->with('success', 'Realisasi biaya berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating realization: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui realisasi. Silakan coba lagi.');
        }
    }

    public function destroy(Request $request, Realization $realization)
    {
        $user = $request->user();

        // Check authorization
        $sppd = $realization->sppd;
        if ($sppd->spt->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow deletion for issued SPPDs
        if ($sppd->status !== 'issued') {
            abort(403, 'Cannot delete realizations for this SPPD.');
        }

        try {
            DB::beginTransaction();

            // Delete file if exists
            if ($realization->file_path) {
                Storage::disk('public')->delete($realization->file_path);
            }

            // Delete realization
            $realization->delete();

            DB::commit();

            return redirect()->route('sppds.show', $sppd->id)
                ->with('success', 'Realisasi biaya berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting realization: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus realisasi. Silakan coba lagi.');
        }
    }

    public function completeSPPD(Request $request, SPPD $sppd)
    {
        $user = $request->user();

        // Check authorization
        if ($sppd->spt->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow completion for issued SPPDs with realizations
        if ($sppd->status !== 'issued' || $sppd->realizations->count() === 0) {
            abort(403, 'Cannot complete this SPPD.');
        }

        try {
            DB::beginTransaction();

            // Update SPPD status
            $sppd->update(['status' => 'completed']);

            DB::commit();

            return redirect()->route('sppds.show', $sppd->id)
                ->with('success', 'SPPD telah ditandai sebagai selesai!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing SPPD: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyelesaikan SPPD. Silakan coba lagi.');
        }
    }

    public function downloadFile(Request $request, Realization $realization)
    {
        $user = $request->user();

        // Check authorization
        $sppd = $realization->sppd;
        if ($sppd->spt->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$realization->file_path) {
            abort(404, 'File not found.');
        }

        $filePath = storage_path('app/public/' . $realization->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }
}