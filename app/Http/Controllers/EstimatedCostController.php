<?php

namespace App\Http\Controllers;

use App\Models\EstimatedCost;
use App\Models\SPT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstimatedCostController extends Controller
{
    /**
     * Display a listing of estimated costs.
     */
    public function index(Request $request)
    {
        $query = EstimatedCost::with(['spt.user']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'ilike', "%{$search}%")
                  ->orWhere('type', 'ilike', "%{$search}%")
                  ->orWhereHas('spt', function($sptQuery) use ($search) {
                      $sptQuery->where('title', 'ilike', "%{$search}%")
                             ->orWhere('destination', 'ilike', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Filter by SPT status
        if ($request->filled('spt_status')) {
            $query->whereHas('spt', function($q) use ($request) {
                $q->where('status', $request->spt_status);
            });
        }

        $estimatedCosts = $query->orderBy('created_at', 'desc')
                               ->paginate(15)
                               ->withQueryString();

        $types = ['transport', 'daily', 'accommodation', 'other'];
        $sptStatuses = ['draft', 'submitted', 'approved', 'rejected'];

        return view('estimated-costs.index', compact(
            'estimatedCosts', 'types', 'sptStatuses'
        ));
    }

    /**
     * Show the form for creating a new estimated cost.
     */
    public function create()
    {
        $spts = SPT::with('user')
                  ->whereIn('status', ['draft', 'submitted'])
                  ->orderBy('created_at', 'desc')
                  ->get();

        $types = ['transport', 'daily', 'accommodation', 'other'];

        return view('estimated-costs.create', compact('spts', 'types'));
    }

    /**
     * Store a newly created estimated cost in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'spt_id' => 'required|exists:spts,id',
            'type' => 'required|in:transport,daily,accommodation,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ], [
            'spt_id.required' => 'SPT wajib dipilih.',
            'spt_id.exists' => 'SPT tidak ditemukan.',
            'type.required' => 'Jenis biaya wajib dipilih.',
            'type.in' => 'Jenis biaya tidak valid.',
            'amount.required' => 'Jumlah biaya wajib diisi.',
            'amount.numeric' => 'Jumlah biaya harus berupa angka.',
            'amount.min' => 'Jumlah biaya tidak boleh kurang dari 0.',
            'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $estimatedCost = EstimatedCost::create($validated);

            // Log activity
            Log::info('Estimated cost created', [
                'estimated_cost_id' => $estimatedCost->id,
                'spt_id' => $estimatedCost->spt_id,
                'user_id' => auth()->id(),
                'action' => 'created'
            ]);

            DB::commit();

            return redirect()->route('estimated-costs.show', $estimatedCost)
                ->with('success', 'Estimasi biaya berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating estimated cost: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan estimasi biaya. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified estimated cost.
     */
    public function show(EstimatedCost $estimatedCost)
    {
        $estimatedCost->load(['spt.user']);
        return view('estimated-costs.show', compact('estimatedCost'));
    }

    /**
     * Show the form for editing the specified estimated cost.
     */
    public function edit(EstimatedCost $estimatedCost)
    {
        // Check authorization
        $spt = $estimatedCost->spt;
        if (!auth()->user()->isAdmin() &&
            $spt->user_id !== auth()->id() &&
            !auth()->user()->hasRole(['supervisor', 'finance'])) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow editing if SPT is in draft status
        if ($spt->status !== 'draft' && !auth()->user()->isAdmin()) {
            abort(403, 'Cannot edit estimated cost for submitted SPT.');
        }

        $types = ['transport', 'daily', 'accommodation', 'other'];
        return view('estimated-costs.edit', compact('estimatedCost', 'types'));
    }

    /**
     * Update the specified estimated cost in storage.
     */
    public function update(Request $request, EstimatedCost $estimatedCost)
    {
        // Check authorization
        $spt = $estimatedCost->spt;
        if (!auth()->user()->isAdmin() &&
            $spt->user_id !== auth()->id() &&
            !auth()->user()->hasRole(['supervisor', 'finance'])) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow updating if SPT is in draft status
        if ($spt->status !== 'draft' && !auth()->user()->isAdmin()) {
            abort(403, 'Cannot update estimated cost for submitted SPT.');
        }

        $validated = $request->validate([
            'type' => 'required|in:transport,daily,accommodation,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ], [
            'type.required' => 'Jenis biaya wajib dipilih.',
            'type.in' => 'Jenis biaya tidak valid.',
            'amount.required' => 'Jumlah biaya wajib diisi.',
            'amount.numeric' => 'Jumlah biaya harus berupa angka.',
            'amount.min' => 'Jumlah biaya tidak boleh kurang dari 0.',
            'description.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $oldValues = $estimatedCost->only(['type', 'amount', 'description']);
            $estimatedCost->update($validated);
            $newValues = $estimatedCost->only(['type', 'amount', 'description']);

            // Log activity
            Log::info('Estimated cost updated', [
                'estimated_cost_id' => $estimatedCost->id,
                'spt_id' => $estimatedCost->spt_id,
                'user_id' => auth()->id(),
                'action' => 'updated',
                'old_values' => $oldValues,
                'new_values' => $newValues
            ]);

            DB::commit();

            return redirect()->route('estimated-costs.show', $estimatedCost)
                ->with('success', 'Estimasi biaya berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating estimated cost: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui estimasi biaya. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified estimated cost from storage.
     */
    public function destroy(EstimatedCost $estimatedCost)
    {
        // Check authorization
        $spt = $estimatedCost->spt;
        if (!auth()->user()->isAdmin() &&
            $spt->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow deletion if SPT is in draft status
        if ($spt->status !== 'draft' && !auth()->user()->isAdmin()) {
            abort(403, 'Cannot delete estimated cost for submitted SPT.');
        }

        try {
            DB::beginTransaction();

            $estimatedCostData = $estimatedCost->toArray();
            $estimatedCost->delete();

            // Log activity
            Log::info('Estimated cost deleted', [
                'estimated_cost_id' => $estimatedCost->id,
                'spt_id' => $estimatedCost->spt_id,
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'deleted_data' => $estimatedCostData
            ]);

            DB::commit();

            return redirect()->route('estimated-costs.index')
                ->with('success', 'Estimasi biaya berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting estimated cost: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus estimasi biaya. Silakan coba lagi.');
        }
    }

    /**
     * Duplicate estimated cost from another SPT.
     */
    public function duplicate(Request $request)
    {
        $validated = $request->validate([
            'source_spt_id' => 'required|exists:spts,id',
            'target_spt_id' => 'required|exists:spts,id|different:source_spt_id',
        ]);

        try {
            DB::beginTransaction();

            $sourceCosts = EstimatedCost::where('spt_id', $validated['source_spt_id'])->get();

            foreach ($sourceCosts as $sourceCost) {
                EstimatedCost::create([
                    'spt_id' => $validated['target_spt_id'],
                    'type' => $sourceCost->type,
                    'amount' => $sourceCost->amount,
                    'description' => $sourceCost->description,
                ]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Estimasi biaya berhasil disalin!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating estimated costs: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyalin estimasi biaya. Silakan coba lagi.');
        }
    }

    /**
     * Bulk operations for estimated costs
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,update_type',
            'cost_ids' => 'required|array',
            'cost_ids.*' => 'exists:estimated_costs,id',
            'new_type' => 'required_if:action,update_type|in:transport,daily,accommodation,other',
        ]);

        $costIds = $request->cost_ids;
        $action = $request->action;

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'delete':
                    // Check if associated SPTs are still in draft status
                    $costsWithSubmittedSPT = EstimatedCost::whereIn('id', $costIds)
                        ->whereHas('spt', function($query) {
                            $query->where('status', '!=', 'draft');
                        })
                        ->count();

                    if ($costsWithSubmittedSPT > 0 && !auth()->user()->isAdmin()) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Beberapa estimasi biaya tidak dapat dihapus karena SPT terkait sudah diajukan.');
                    }

                    EstimatedCost::whereIn('id', $costIds)->delete();
                    $message = 'Estimasi biaya yang dipilih berhasil dihapus!';
                    break;

                case 'update_type':
                    EstimatedCost::whereIn('id', $costIds)
                                 ->update(['type' => $request->new_type]);
                    $message = 'Tipe estimasi biaya yang dipilih berhasil diperbarui!';
                    break;
            }

            DB::commit();

            return redirect()->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk estimated cost action: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat melakukan operasi批量. Silakan coba lagi.');
        }
    }

    /**
     * Export estimated costs to CSV
     */
    public function export(Request $request)
    {
        $query = EstimatedCost::with(['spt.user']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'ilike', "%{$search}%")
                  ->orWhere('type', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $costs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'estimated_costs_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($costs) {
            $file = fopen('php://output', 'w');

            // Add CSV header
            fputcsv($file, [
                'ID', 'SPT', 'Pemohon', 'Jenis Biaya', 'Jumlah',
                'Deskripsi', 'Dibuat Pada'
            ]);

            // Add data rows
            foreach ($costs as $cost) {
                fputcsv($file, [
                    $cost->id,
                    'SPT #' . $cost->spt_id . ' - ' . $cost->spt->title,
                    $cost->spt->user->name,
                    $cost->type,
                    $cost->amount,
                    $cost->description ?? '',
                    $cost->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}