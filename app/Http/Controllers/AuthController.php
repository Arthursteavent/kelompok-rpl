<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Halaman Login (User juga bisa memilih lanjut tanpa login sebagai Penglihat).
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi Login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            return redirect()
                ->intended(route('home'))
                ->with('success', "Selamat datang, {$user->name}! Anda login sebagai {$user->roleLabel()}.");
        }

        return back()
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.'])
            ->onlyInput('email');
    }

    /**
     * Halaman Registrasi Akun Baru (Khusus pendaftaran sebagai User Penglihat).
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Proses penyimpanan akun baru dari halaman publik (Otomatis hanya sebagai User Penglihat).
     * Pendaftaran Penulis atau User oleh Admin dilakukan di halaman khusus Admin.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => User::ROLE_PENGLIHAT,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', "Akun berhasil dibuat! Selamat datang, {$user->name} (User Penglihat).");
    }

    /**
     * Proses Logout. Setelah logout user tetap bisa melihat berita tanpa login.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Anda telah logout. Anda tetap dapat membaca berita sebagai pengunjung (tanpa login).');
    }
}
