<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos; // pastikan model Kos sudah dibuat
use Illuminate\Support\Facades\Auth;

class KosController extends Controller
{
    // Menampilkan form tambah kos
    public function create()
    {
        return view('kos.create');
    }

    // Menyimpan data kos yang ditambahkan
    public function store(Request $request)
    {
        $request->validate([
            'nama_kos' => 'required|string|max:255',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'fasilitas' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_kos', 'public');
        }

        Kos::create([
            'user_id' => Auth::id(), // atau auth()->id()
            'nama_kos' => $request->nama_kos,
            'deskripsi' => $request->deskripsi,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('kos.create')->with('success', 'Kos berhasil ditambahkan!');
    }
}