<?php

namespace App\Http\Controllers;

use App\Models\User_profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users_profile = User_profile::all();
        return view('user_profile.index', compact('users_profile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user_profile.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi dengan trimming spasi pada validation rules
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users_profile,email',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'price_range'  => 'nullable|string', // Menghapus validasi in: untuk memungkinkan fleksibilitas
            'location'     => 'nullable|string|max:255',
            'room_type'    => 'nullable|string', // Menghapus validasi in: untuk memungkinkan fleksibilitas
            'facilities'   => 'nullable|array',
            'facilities.*' => 'nullable|string', // Menghapus validasi in: untuk memungkinkan fleksibilitas
            'photo'        => 'nullable|image|max:2048',
        ]);

        // Debug: dd($request->all()); // Uncomment untuk melihat data yang dikirim

        // Konversi array facilities menjadi string jika ada
        if (isset($data['facilities']) && is_array($data['facilities'])) {
            // Filter nilai null atau empty string
            $data['facilities'] = implode(',', array_filter($data['facilities'], function($value) {
                return !is_null($value) && $value !== '';
            }));
        } else {
            $data['facilities'] = null;
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        try {
            User_profile::create($data);
            return redirect()->route('user_profile.index')->with('success', 'Profile created successfully.');
        } catch (\Exception $e) {
            // Debug: Log error or display it
            return back()->withInput()->withErrors(['error' => 'Failed to save profile: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users_profile = User_profile::findOrFail($id);
        return view('user_profile.show', compact('users_profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users_profile = User_profile::findOrFail($id);
        return view('user_profile.edit', compact('users_profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users_profile = User_profile::findOrFail($id);

        // Validasi dengan trimming spasi pada validation rules
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users_profile,email,' . $id,
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'price_range'  => 'nullable|string', // Menghapus validasi in: untuk memungkinkan fleksibilitas
            'location'     => 'nullable|string|max:255',
            'room_type'    => 'nullable|string', // Menghapus validasi in: untuk memungkinkan fleksibilitas
            'facilities'   => 'nullable|array',
            'facilities.*' => 'nullable|string', // Menghapus validasi in: untuk memungkinkan fleksibilitas
            'photo'        => 'nullable|image|max:2048',
        ]);

        // Konversi array facilities menjadi string jika ada
        if (isset($data['facilities']) && is_array($data['facilities'])) {
            // Filter nilai null atau empty string
            $data['facilities'] = implode(',', array_filter($data['facilities'], function($value) {
                return !is_null($value) && $value !== '';
            }));
        } else {
            $data['facilities'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($users_profile->photo) {
                Storage::disk('public')->delete($users_profile->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        try {
            $users_profile->update($data);
            return redirect()->route('user_profile.index')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            // Debug: Log error or display it
            return back()->withInput()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users_profile = User_profile::findOrFail($id);
        if ($users_profile->photo) {
            Storage::disk('public')->delete($users_profile->photo);
        }
        $users_profile->delete();
        return redirect()->route('user_profile.index')->with('success', 'Profile deleted successfully.');
    }
}