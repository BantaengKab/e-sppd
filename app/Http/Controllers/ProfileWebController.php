<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileWebController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return back()->with('status', 'profile-updated');
    }

    public function users(Request $request)
    {
        $users = User::orderBy('name')->paginate(10);

        return view('users.index', compact('users'));
    }
}
