    public function edit(User $user)
    {
        // If it's an AJAX request, return just the form content
        if (request()->ajax() || request()->wantsJson()) {
            return view('users.edit', compact('user'));
        }
        
        // Otherwise return full page
        return view('users.edit', compact('user'));
    }

    public function show(User $user)
    {
        // If it's an AJAX request, return just the detail content
        if (request()->ajax() || request()->wantsJson()) {
            return view('users.show', compact('user'));
        }
        
        // Otherwise return full page
        return view('users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nip' => 'required|string|unique:users,nip,' . $user->id,
            'role' => 'required|in:admin,supervisor,finance,verifikator,user',
            'jabatan' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Remove password if not provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diupdate!'
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diupdate!');
    }