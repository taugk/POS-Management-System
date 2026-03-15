<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AutoMigrate
{
    public function handle($request, Closure $next)
    {
        if (Schema::hasTable('migrations')) {

            Artisan::call('migrate', [
                '--force' => true
            ]);

        }

        return $next($request);
    }
}