<?php

namespace App\Http\Controllers;

use App\Models\ManajemenProfilPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ManajemenProfilPenggunaController extends Controller
{
    // GET: /pengguna
    public function index()
    {
        return ManajemenProfilPengguna::all();
    }

    // POST: /pengguna
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'preferensi_pencarian' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validasi foto profil
            'email' => 'required|email|unique:manajemen_profil_pengguna,email',
            'password' => 'required|min:6',
        ]);

        if ($request->hasFile('foto_profil')) {
            // Upload foto profil
            $path = $request->file('foto_profil')->store('profile_photos', 'public');
            $validated['foto_profil'] = $path; // Menyimpan path file foto
        }

        $validated['password'] = Hash::make($validated['password']);

        $pengguna = ManajemenProfilPengguna::create($validated);

        return response()->json($pengguna, 201);
    }

    // GET: /pengguna/{id}
    public function show($id)
    {
        return ManajemenProfilPengguna::findOrFail($id);
    }

    // PUT: /pengguna/{id}
    public function update(Request $request, $id)
    {
        $pengguna = ManajemenProfilPengguna::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'preferensi_pencarian' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validasi foto profil
        ]);

        // Update data profil
        if ($request->hasFile('foto_profil')) {
            // Jika ada foto profil baru
            if ($pengguna->foto_profil) {
                // Hapus foto lama jika ada
                Storage::delete('public/' . $pengguna->foto_profil);
            }

            // Upload foto profil baru
            $path = $request->file('foto_profil')->store('profile_photos', 'public');
            $validated['foto_profil'] = $path;
        }

        $pengguna->update($validated);

        return response()->json($pengguna);
    }

    // DELETE: /pengguna/{id}
    public function destroy($id)
    {
        $pengguna = ManajemenProfilPengguna::findOrFail($id);

        // Hapus foto profil jika ada
        if ($pengguna->foto_profil) {
            Storage::delete('public/' . $pengguna->foto_profil);
        }

        $pengguna->delete();

        return response()->json(['message' => 'Akun berhasil dihapus.']);
    }
}
