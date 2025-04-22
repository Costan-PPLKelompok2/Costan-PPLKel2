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
            'nama_kos' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'fasilitas' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->file('foto') ? $request->file('foto')->store('foto_kos', 'public') : null;

        Kos::create([
            'user_id' => auth()->id(),
            'nama_kos' => $request->nama_kos,
            'deskripsi' => $request->deskripsi,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'fasilitas' => $request->fasilitas,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('kos.create')->with('success', 'Kos berhasil ditambahkan.');
    }
}