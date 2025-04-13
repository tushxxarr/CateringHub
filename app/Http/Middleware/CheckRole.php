<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if ($role == 'merchant' && !$user->isMerchant()) {
            return redirect()->route('home')->with('error', 'You do not have access to this section.');
        }

        if ($role == 'customer' && !$user->isCustomer()) {
            return redirect()->route('home')->with('error', 'You do not have access to this section.');
        }

        if ($role == 'admin' && !$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'You do not have access to this section.');
        }

        return $next($request);
    }
}
