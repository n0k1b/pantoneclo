<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        if (env('MAINTENANCE_MODE')==='ON') {
            return redirect('/maintenance-mode');
        }
        return $next($request);
    }
}
