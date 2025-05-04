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

    // Tampilkan form edit
    public function edit($id)
    {
        $kos = \App\Models\Kos::where('user_id', auth()->id())->findOrFail($id);
        return view('kos.edit', compact('kos'));
    }

    // Simpan hasil edit
    public function update(Request $request, $id)
    {
        $kos = \App\Models\Kos::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'nama_kos' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
            'harga' => 'required|numeric',
            'fasilitas' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama_kos', 'deskripsi', 'alamat', 'harga', 'fasilitas']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos', 'public');
        }

        $kos->update($data);

        return redirect()->route('kos.index')->with('success', 'Data kos berhasil diperbarui!');
    }

    // Hapus data kos
    public function destroy($id)
    {
        $kos = \App\Models\Kos::where('user_id', auth()->id())->findOrFail($id);
        $kos->delete();

        return redirect()->route('kos.index')->with('success', 'Kos berhasil dihapus!');
    }


    // Menampilkan di dashboard
    public function index()
    {
        $kosList = \App\Models\Kos::where('user_id', auth()->id())->get();
        return view('kos.index', compact('kosList'));
    }

}
