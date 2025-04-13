<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\MerchantProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showMerchantRegistrationForm()
    {
        return view('auth.merchant-register');
    }

    public function showCustomerRegistrationForm()
    {
        return view('auth.customer-register');
    }

    public function registerMerchant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->company_name, // Sinkron dengan nama perusahaan
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'merchant',
        ]);

        $profileData = [
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'description' => $request->description,
        ];

        if ($request->hasFile('logo')) {
            $profileData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        MerchantProfile::create($profileData);

        auth()->login($user);

        return redirect()->route('merchant.dashboard')->with('success', 'Registration successful!');
    }

    public function registerCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        CustomerProfile::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'description' => $request->description,
        ]);

        auth()->login($user);

        return redirect()->route('login');
    }
}
