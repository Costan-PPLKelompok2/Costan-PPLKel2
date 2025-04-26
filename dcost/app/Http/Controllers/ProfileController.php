<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile_management.show', compact('user'));
    }

    /**
     * Menampilkan form edit profil pengguna.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile_management.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'search_preferences' => 'nullable|array',
            'price_min' => 'nullable|numeric',
            'price_max' => 'nullable|numeric',
            'preferred_location' => 'nullable|string|max:255',
            'preferred_kos_type' => 'nullable|string|max:255',
        ]);

        // Upload foto profil kalau ada
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Update data user
        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'search_preferences' => $request->search_preferences,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
            'preferred_location' => $request->preferred_location,
            'preferred_kos_type' => $request->preferred_kos_type,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menghapus akun pengguna (soft delete).
     */
    public function destroy()
    {
        $user = Auth::user();

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus.');
    }
}