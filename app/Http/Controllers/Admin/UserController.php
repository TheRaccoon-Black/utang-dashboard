<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Halaman manajemen user.
     */
    public function index(): View
    {
        $users = User::query()->orderBy('created_at')->get();

        return view('admin.users', [
            'users' => $users,
            'roleOptions' => User::ROLES,
        ]);
    }

    /**
     * Tambah user baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:' . implode(',', array_keys(User::ROLES))],
        ]);

        User::create($validated);

        return redirect()
            ->route('admin.users')
            ->with('success', "User {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Ubah data user (nama, email, role, dan opsional reset password).
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:' . implode(',', array_keys(User::ROLES))],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Jangan izinkan admin menghapus status admin dirinya sendiri
        // saat tidak ada admin lain.
        if ($user->id === $request->user()->id && $user->isAdmin() && $validated['role'] !== User::ROLE_ADMIN) {
            $totalAdmin = User::where('role', User::ROLE_ADMIN)->count();
            if ($totalAdmin <= 1) {
                return back()->withInput()->withErrors([
                    'role' => 'Tidak bisa menurunkan role admin terakhir. Tambahkan admin lain dulu.',
                ]);
            }
        }

        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()
            ->route('admin.users')
            ->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    /**
     * Hapus user. Tidak boleh menghapus diri sendiri.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', "User {$user->name} berhasil dihapus.");
    }
}
