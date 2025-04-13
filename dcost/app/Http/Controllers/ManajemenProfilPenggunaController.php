<?php

namespace App\Http\Controllers;

use App\Models\ManajemenProfilPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'foto_profil' => 'nullable|string',
            'email' => 'required|email|unique:manajemen_profil_pengguna,email',
            'password' => 'required|min:6',
        ]);

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

        $pengguna->update($request->only([
            'nama', 'nomor_telepon', 'preferensi_pencarian', 'foto_profil'
        ]));

        return response()->json($pengguna);
    }

    // DELETE: /pengguna/{id}
    public function destroy($id)
    {
        $pengguna = ManajemenProfilPengguna::findOrFail($id);
        $pengguna->delete();

        return response()->json(['message' => 'Akun berhasil dihapus.']);
    }
}
