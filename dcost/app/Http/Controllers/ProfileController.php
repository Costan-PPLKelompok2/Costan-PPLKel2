<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = Auth::user();
        $data = $request->only(['name', 'phone_number', 'address']);

        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::delete('public/' . $user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:DELETE',
        ]);

        $user = Auth::user();
        if ($user->profile_photo_path) {
            Storage::delete('public/' . $user->profile_photo_path);
        }
        $user->delete();

        Auth::logout();
        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}