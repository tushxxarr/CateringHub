<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->customerProfile;
        return view('customer.profile.show', compact('profile'));
    }

    public function edit()
    {
        $profile = Auth::user()->customerProfile;
        return view('customer.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $profile = Auth::user()->customerProfile;
        $profile->company_name = $request->company_name;
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        $profile->description = $request->description;
        $profile->save();

        return redirect()->route('customer.profile.show')->with('success', 'Profile updated successfully.');
    }
}
