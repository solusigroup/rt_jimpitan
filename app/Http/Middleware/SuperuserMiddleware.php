<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperuserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $adminPassword = config('app.rt_admin_password', env('RT_ADMIN_PASSWORD', 'pengurusRT35'));
        $legacyToken = "rt035jimpitan2026";
        $token = $request->query('token');

        if ($token === $adminPassword || $token === $legacyToken) {
            session(['superuser' => true]);
        }

        if (!session('superuser')) {
            return redirect()->route('login')->with('error', 'Anda harus masuk sebagai pengurus terlebih dahulu.');
        }

        return $next($request);
    }
}
