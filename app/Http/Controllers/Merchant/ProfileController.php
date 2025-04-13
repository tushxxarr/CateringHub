<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->merchantProfile;
        return view('merchant.profile.show', compact('profile'));
    }

    public function edit()
    {
        $profile = Auth::user()->merchantProfile;
        return view('merchant.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $profile = Auth::user()->merchantProfile;
        $profile->company_name = $request->company_name;
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        $profile->description = $request->description;

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $profile->logo = $request->file('logo')->store('logos', 'public');
        }

        $profile->save();

        return redirect()->route('merchant.profile.show')->with('success', 'Profile updated successfully.');
    }
}
