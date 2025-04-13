<?php

namespace App\Http\Controllers;

use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on the user's role
            if ($user->isMerchant()) {
                return redirect()->route('merchant.dashboard');
            } elseif ($user->isCustomer()) {
                return redirect()->route('customer.dashboard');
            } elseif ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
        }

        // Fetch 4 random merchants from the database
        $featuredMerchants = MerchantProfile::inRandomOrder()->take(4)->get();

        // Return the view with the fetched merchants
        return view('home', compact('featuredMerchants'));
    }
}
