<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = file_exists(storage_path('installed'));
        $isInstallRoute = $request->is('install*');

        // Skenario 1: Belum install, tapi mencoba akses aplikasi utama
        if (!$isInstalled && !$isInstallRoute) {
            return redirect()->route('installer.database');
        }

        // Skenario 2: Sudah install, tapi mencoba nakal akses installer lagi
        if ($isInstalled && $isInstallRoute) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}