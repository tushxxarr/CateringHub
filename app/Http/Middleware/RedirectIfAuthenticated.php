<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect berbeda berdasarkan role
                if (Auth::guard($guard)->user()->role === 'merchant') {
                    return redirect('/merchant/dashboard');
                } elseif (Auth::guard($guard)->user()->role === 'customer') {
                    return redirect('/customer/dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
