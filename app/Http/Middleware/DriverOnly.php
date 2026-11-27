<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class DriverOnly {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || !auth()->user()->isDriver()) {
            abort(403, 'Akses ditolak. Hanya Driver yang dapat mengakses halaman ini.');
        }
        return $next($request);
    }
}
