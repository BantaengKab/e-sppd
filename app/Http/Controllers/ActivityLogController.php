<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'ilike', "%{$search}%")
                  ->orWhere('table_name', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by table
        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->orderBy('created_at', 'desc')
                             ->paginate(20)
                             ->withQueryString();

        $users = User::orderBy('name')->pluck('name', 'id');
        $actions = ['created', 'updated', 'deleted', 'submitted', 'approved', 'rejected'];
        $tables = ['spts', 'estimated_costs', 'approvals', 'sppds', 'realizations', 'users'];

        return view('activity-logs.index', compact(
            'activityLogs', 'users', 'actions', 'tables'
        ));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Show activity log statistics.
     */
    public function statistics(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->subDays(30);
        $dateTo = $request->filled('date_to') ? $request->date_to : now();

        $query = ActivityLog::whereBetween('created_at', [$dateFrom, $dateTo]);

        $stats = [
            'total_logs' => $query->count(),
            'by_action' => $query->selectRaw('action, COUNT(*) as count')
                              ->groupBy('action')
                              ->orderBy('count', 'desc')
                              ->get(),
            'by_table' => $query->selectRaw('table_name, COUNT(*) as count')
                              ->groupBy('table_name')
                              ->orderBy('count', 'desc')
                              ->get(),
            'by_user' => $query->with('user')
                              ->selectRaw('user_id, COUNT(*) as count')
                              ->groupBy('user_id')
                              ->orderBy('count', 'desc')
                              ->limit(10)
                              ->get(),
            'daily_trend' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                               ->groupBy('date')
                               ->orderBy('date')
                               ->get(),
        ];

        return view('activity-logs.statistics', compact('stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Export activity logs to CSV.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'ilike', "%{$search}%")
                  ->orWhere('table_name', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Add CSV header
            fputcsv($file, [
                'ID', 'User', 'Action', 'Table Name', 'Record ID',
                'IP Address', 'User Agent', 'Created At'
            ]);

            // Add data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    $log->table_name,
                    $log->record_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete old activity logs (cleanup).
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365',
        ]);

        try {
            $cutoffDate = now()->subDays($request->days);

            $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

            return redirect()->back()
                ->with('success', "Berhasil menghapus {$deletedCount} log aktivitas yang lebih lama dari {$request->days} hari.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membersihkan log aktivitas.');
        }
    }

    /**
     * Bulk delete activity logs.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:activity_logs,id',
        ]);

        try {
            $deletedCount = ActivityLog::whereIn('id', $request->log_ids)->delete();

            return redirect()->back()
                ->with('success', "Berhasil menghapus {$deletedCount} log aktivitas.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus log aktivitas.');
        }
    }
}