<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request and verify user role.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
        }

        if (! in_array($user->role, $roles, true)) {
            $allowedLabels = collect($roles)->map(fn (string $r) => match ($r) {
                'admin' => 'Admin',
                'penulis' => 'Penulis',
                'penglihat' => 'User Penglihat',
                default => ucfirst($r),
            })->implode(' / ');

            return redirect()
                ->route('home')
                ->with('error', "Akses ditolak! Halaman tersebut hanya dapat diakses oleh role: {$allowedLabels}.");
        }

        return $next($request);
    }
}

