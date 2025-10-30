<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UsersController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('nip', 'ilike', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by unit kerja
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', 'ilike', "%{$request->unit_kerja}%");
        }

        $users = $query->orderBy('created_at', 'desc')
                      ->paginate(10)
                      ->withQueryString();

        $roles = ['admin', 'supervisor', 'finance', 'verifikator', 'employee'];
        $unitKerjas = User::distinct()->pluck('unit_kerja')->filter()->values();

        return view('users.index', compact('users', 'roles', 'unitKerjas'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['admin', 'supervisor', 'finance', 'verifikator', 'employee'];
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'required|string|max:50|unique:users',
            'role' => ['required', Rule::in(['admin', 'supervisor', 'finance', 'verifikator', 'employee'])],
            'jabatan' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'nip' => $validated['nip'],
                'role' => $validated['role'],
                'jabatan' => $validated['jabatan'],
                'unit_kerja' => $validated['unit_kerja'],
            ]);

            DB::commit();

            // Handle AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna baru berhasil ditambahkan!',
                    'user' => $user,
                    'reload' => true
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Pengguna baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());

            // Handle AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambahkan pengguna. Silakan coba lagi.',
                    'errors' => []
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['spts', 'approvals', 'activityLogs' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'supervisor', 'finance', 'verifikator', 'employee'];
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'nip' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'supervisor', 'finance', 'verifikator', 'employee'])],
            'jabatan' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'unit_kerja.required' => 'Unit kerja wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nip' => $validated['nip'],
                'role' => $validated['role'],
                'jabatan' => $validated['jabatan'],
                'unit_kerja' => $validated['unit_kerja'],
            ];

            // Update password only if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            DB::commit();

            // Handle AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data pengguna berhasil diperbarui!',
                    'user' => $user->fresh(),
                    'reload' => true
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Data pengguna berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());

            // Handle AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui pengguna. Silakan coba lagi.',
                    'errors' => []
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Prevent deletion of the current authenticated user
        if ($user->id === auth()->id()) {
            $errorMessage = 'Tidak dapat menghapus akun yang sedang digunakan.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 403);
            }

            return redirect()->back()->with('error', $errorMessage);
        }

        // Check if user has related records
        if ($user->spts()->count() > 0 || $user->approvals()->count() > 0) {
            $errorMessage = 'Pengguna tidak dapat dihapus karena memiliki data terkait (SPT atau persetujuan).';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }

            return redirect()->back()->with('error', $errorMessage);
        }

        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            $successMessage = 'Pengguna berhasil dihapus!';

            // Handle AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'reload' => true
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            $errorMessage = 'Terjadi kesalahan saat menghapus pengguna. Silakan coba lagi.';

            // Handle AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->back()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        // Prevent status change of current user
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengubah status akun yang sedang digunakan.');
        }

        try {
            $user->update([
                'status' => $user->status === 'active' ? 'inactive' : 'active'
            ]);

            $status = $user->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()->back()
                ->with('success', "Pengguna berhasil {$status}!");

        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status pengguna.');
        }
    }

    /**
     * Bulk operations for users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        $action = $request->action;

        // Prevent including current user
        if (in_array(auth()->id(), $userIds)) {
            return redirect()->back()
                ->with('error', 'Tidak dapat melakukan operasi pada akun yang sedang digunakan.');
        }

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'delete':
                    // Check for related records before deletion
                    $usersWithRecords = User::whereIn('id', $userIds)
                        ->where(function($query) {
                            $query->has('spts')
                                  ->orHas('approvals');
                        })
                        ->count();

                    if ($usersWithRecords > 0) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Beberapa pengguna tidak dapat dihapus karena memiliki data terkait.');
                    }

                    User::whereIn('id', $userIds)->delete();
                    $message = 'Pengguna yang dipilih berhasil dihapus!';
                    break;

                case 'activate':
                    User::whereIn('id', $userIds)->update(['status' => 'active']);
                    $message = 'Pengguna yang dipilih berhasil diaktifkan!';
                    break;

                case 'deactivate':
                    User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                    $message = 'Pengguna yang dipilih berhasil dinonaktifkan!';
                    break;
            }

            DB::commit();

            return redirect()->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk user action: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat melakukan operasi批量. Silakan coba lagi.');
        }
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = User::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('nip', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->get();

        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // Add CSV header
            fputcsv($file, [
                'ID', 'Nama', 'Email', 'NIP', 'Role', 'Jabatan',
                'Unit Kerja', 'Status', 'Tanggal Dibuat'
            ]);

            // Add data rows
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->nip,
                    $user->role,
                    $user->jabatan,
                    $user->unit_kerja,
                    $user->status ?? 'active',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}