<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Halaman Admin: Form Daftarkan Penulis / User & Tabel Daftar Pengguna.
     */
    public function index(): View
    {
        $users = User::orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'penulis' THEN 2 ELSE 3 END")
            ->latest()
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Simpan akun baru yang didaftarkan oleh Admin (Bisa pilih jadi Penulis atau User Penglihat).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in([User::ROLE_PENULIS, User::ROLE_PENGLIHAT])],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', "Akun \"{$user->name}\" berhasil didaftarkan sebagai {$user->roleLabel()}!");
    }

    /**
     * Perbarui data atau Role pengguna oleh Admin.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_PENULIS, User::ROLE_PENGLIHAT])],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', "Data pengguna \"{$user->name}\" ({$user->roleLabel()}) berhasil diperbarui!");
    }

    /**
     * Hapus pengguna oleh Admin.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->id === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Admin yang sedang digunakan.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "Akun pengguna \"{$name}\" berhasil dihapus.");
    }
}

