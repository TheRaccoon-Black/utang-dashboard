<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Hanya role admin yang boleh melewati. Role lain dialihkan ke dashboard publik
     * dengan pesan flash.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->isAdmin()) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}
