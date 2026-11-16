<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeliveryAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('delivery')->check()) {
            return redirect()->route('delivery.login');
        }
        return $next($request);
    }
}