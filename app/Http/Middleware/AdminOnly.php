<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();


        if (!Auth::check() || !$user->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
        }   

        return $next($request);
    }
}