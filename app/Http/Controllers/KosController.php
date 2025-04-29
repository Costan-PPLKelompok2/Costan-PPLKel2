<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class KosController extends Controller
{
    /**
     * Tampilkan daftar kos dengan filter:
     * - search (nama_kos, alamat, fasilitas)
     * - price_min & price_max
     * - fasilitas substring
     * - status_ketersediaan
     */
    public function index(Request $request)
    {
        $query = Kos::query();

        // 1. Filter pencarian kata kunci
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_kos', 'like', "%{$keyword}%")
                  ->orWhere('alamat',    'like', "%{$keyword}%")
                  ->orWhere('fasilitas', 'like', "%{$keyword}%");
            });
        }

        // 2. Filter harga
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('harga', [
                $request->price_min,
                $request->price_max,
            ]);
        }

        // 3. Filter fasilitas (substring match)
        if ($request->filled('fasilitas')) {
            $query->where('fasilitas', 'like', '%'.$request->fasilitas.'%');
        }

        // 4. Filter status ketersediaan
        if ($request->filled('status_ketersediaan')) {
            $query->where('status_ketersediaan', $request->status_ketersediaan);
        }

        // Ambil hasil paginate dan pertahankan query string
        $kosList = $query->paginate(12)->withQueryString();

        return view('home.dashboard', compact('kosList'));
    }

    /**
     * Tampilkan form tambah kos.
     */
    public function create()
    {
        return view('kos.create');
    }

    /**
     * Simpan kos baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kos'            => 'required|string|max:255',
            'alamat'              => 'required|string|max:255',
            'harga'               => 'required|numeric',
            'fasilitas'           => 'required|string',
            'deskripsi'           => 'nullable|string',
            'status_ketersediaan' => 'required|integer|in:0,1',
        ]);

        Kos::create($data);

        return redirect()->route('kos.index')
                         ->with('success', 'Kos berhasil ditambah.');
    }

    /**
     * Tampilkan form edit kos.
     */
    public function edit($id_kos)
    {
        $kos = Kos::findOrFail($id_kos);
        return view('kos.edit', compact('kos'));
    }

    /**
     * Update data kos.
     */
    public function update(Request $request, $id_kos)
    {
        $data = $request->validate([
            'nama_kos'            => 'required|string|max:255',
            'alamat'              => 'required|string|max:255',
            'harga'               => 'required|numeric',
            'fasilitas'           => 'required|string',
            'deskripsi'           => 'nullable|string',
            'status_ketersediaan' => 'required|integer|in:0,1',
        ]);

        Kos::findOrFail($id_kos)->update($data);

        return redirect()->route('kos.index')
                         ->with('success', 'Kos berhasil diubah.');
    }

    /**
     * Tampilkan detail sebuah kos.
     */
    public function show($id_kos)
    {
        $kos = Kos::findOrFail($id_kos);
        return view('kos.show', compact('kos'));
    }

    /**
     * Hapus kos.
     */
    public function destroy($id_kos)
    {
        Kos::findOrFail($id_kos)->delete();
        return back()->with('success', 'Kos berhasil dihapus.');
    }
}
