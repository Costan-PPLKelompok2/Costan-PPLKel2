<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        $user_id = auth()->id();

        // Mengambil data kos untuk user yang sedang login
        $kosList = Kos::where('user_id', $user_id);

        // Statistik
        $totalKos = Kos::where('user_id', $user_id)->count();
        $totalPenghuni = DB::table('penghuni') // Sesuaikan dengan nama tabel penghuni Anda
            ->whereIn('kos_id', function ($query) use ($user_id) {
                $query->select('id')
                    ->from('kos')
                    ->where('user_id', $user_id);
            })
            ->count();

        // Pencarian
        $search = $request->input('search');
        if ($search) {
            $kosList->where(function ($query) use ($search) {
                $query->where('nama_kos', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        // Pengurutan
        $sort = $request->input('sort'); // Ambil parameter 'sort' dari request
        if ($sort === 'terbaru') {
            $kosList->orderBy('created_at', 'desc');
        } elseif ($sort === 'terlama') {
            $kosList->orderBy('created_at', 'asc');
        } else {
            $kosList->orderBy('created_at', 'desc'); // Default: Terbaru
        }

        $kosList = $kosList->get();

        // Mengirimkan variabel $kosList, $totalKos, dan $totalPenghuni ke view
        return view('kos.index', compact('kosList', 'totalKos', 'totalPenghuni'));
    }
}
