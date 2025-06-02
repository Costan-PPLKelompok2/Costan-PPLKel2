<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $isOwner = ($user->role === 'pemilik_kos');
        return view('profile.edit', compact('user', 'isOwner'));
    }

    public function setRole(Request $request)
    {
        $request->validate([
            'role' => ['required', 'string', Rule::in(['penyewa', 'pemilik_kos'])],
        ]);

        $user = Auth::user();
        $user->role = $request->role;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Peran berhasil disimpan.', 'newRole' => $user->role]);
    }

    public function editPhoto()
    {
        $user = Auth::user();
        return view('profile.edit-photo', compact('user')); 
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->update([
            'profile_photo_path' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        // $isOwner ditentukan dari peran yang sudah tersimpan (setelah pop-up atau jika sudah ada sebelumnya)
        $isOwner = ($user->role === 'pemilik_kos');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            // Foto profil tidak dihandle di sini, karena ada updatePhoto() atau Livewire
        ];

        // Data yang akan diupdate, diinisialisasi di sini
        $updateData = [];

        if (!$isOwner) { // Jika BUKAN pemilik (berarti penyewa)
            $rules['preferred_location'] = 'nullable|string|max:255';
            $rules['price'] = 'nullable|string|max:255';
            $rules['preferred_kos_type'] = 'nullable|string|max:255';
            $rules['preferred_facilities'] = 'nullable|array';
            $rules['preferred_facilities.*'] = 'string|max:100';
        }

        $validatedData = $request->validate($rules);

        // Isi data umum yang selalu ada
        $updateData['name'] = $validatedData['name'];
        $updateData['email'] = $validatedData['email']; // Pastikan $user->email di-update jika validasinya lolos
        $updateData['phone'] = $validatedData['phone'] ?? null;
        $updateData['address'] = $validatedData['address'] ?? null;

        if (!$isOwner) {
            // Isi data preferensi HANYA jika pengguna adalah penyewa
            $updateData['preferred_location'] = $validatedData['preferred_location'] ?? null;
            $updateData['price'] = $validatedData['price'] ?? null;
            $updateData['preferred_kos_type'] = $validatedData['preferred_kos_type'] ?? null;
            // Jika 'preferred_facilities' tidak ada di request (misal semua checkbox tidak dicentang oleh penyewa),
            // maka simpan sebagai array kosong.
            $updateData['preferred_facilities'] = $request->has('preferred_facilities') ? ($validatedData['preferred_facilities'] ?? []) : [];
        } else {
            // Jika user adalah pemilik_kos, pastikan field preferensi penyewa di-set ke null/kosong di database.
            // Ini menjaga konsistensi data jika peran pengguna bisa berubah atau untuk pembersihan.
            $updateData['preferred_location'] = null;
            $updateData['price'] = null;
            $updateData['preferred_kos_type'] = null;
            $updateData['preferred_facilities'] = []; // Sesuai dengan cast 'array' di model User
        }

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Simpan foto baru
            $path = $request->file('photo')->store('profile-photos', 'public');
            $updateData['profile_photo_path'] = $path;
        }

        // Lakukan satu kali update
        $user->update($updateData);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    public function destroy()
    {
        $user = Auth::user();

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus.');
    }
}
