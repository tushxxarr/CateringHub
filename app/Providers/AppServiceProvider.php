<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share cart count with all views
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'customer') {
                $cart = Session::get('cart', []);
                $cartCount = count($cart);
                $view->with('cartCount', $cartCount);
            }
        });
    }
}
