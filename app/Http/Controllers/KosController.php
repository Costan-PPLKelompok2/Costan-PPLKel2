<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KosController extends Controller
{
    public function __construct()
    {
        // Hanya index() dan show() yang boleh diakses publik
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * 1. Daftar publik: search/filter global
     */
    public function index(Request $request)
    {
        $query = Kos::query();

        // keyword search
        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_kos','like',"%{$kw}%")
                  ->orWhere('alamat','like',"%{$kw}%")
                  ->orWhere('fasilitas','like',"%{$kw}%")
            );
        }

        // harga
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('harga', [
                $request->price_min, $request->price_max
            ]);
        }

        // fasilitas substring
        if ($request->filled('fasilitas')) {
            $query->where('fasilitas','like','%'.$request->fasilitas.'%');
        }

        // status
        if ($request->filled('status_ketersediaan')) {
            $query->where('status_ketersediaan',$request->status_ketersediaan);
        }

        // radius ke kampus (opsional, butuh kolom lat/lng + config)
        if ($request->filled('loc_lat') && $request->filled('loc_lng') && $request->filled('radius')) {
            $lat    = $request->loc_lat;
            $lng    = $request->loc_lng;
            $radius = (float)$request->radius;
            $haversine = "(6371 * acos(
                cos(radians(?)) 
              * cos(radians(latitude)) 
              * cos(radians(longitude) - radians(?)) 
              + sin(radians(?)) 
              * sin(radians(latitude))
            ))";
            $query->selectRaw("kos.*, {$haversine} AS distance", [$lat,$lng,$lat])
                  ->having('distance','<=',$radius)
                  ->orderBy('distance');
        }

        // paginate
        $kosList    = $query->paginate(12)->withQueryString();
        return view('home.dashboard', compact('kosList'));
    }

    /**
     * 2. Detail publik
     */
    public function show($id_kos)
    {
        $kos = Kos::findOrFail($id_kos);
        return view('kos.show', compact('kos'));
    }

    /**
     * 3. Daftar milik user
     */
    public function manage()
    {
        $kosList = Kos::where('user_id', Auth::id())
                      ->latest()
                      ->paginate(10);
        return view('kos.manage', compact('kosList'));
    }

    /**
     * 4. Form tambah
     */
    public function create()
    {
        return view('kos.create');
    }

    /**
     * 5. Simpan baru (simpan user_id & foto)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'deskripsi'=> 'required|string',
            'alamat'   => 'required|string|max:255',
            'harga'    => 'required|numeric',
            'fasilitas'=> 'required|string',
            'foto'     => 'nullable|image|max:2048',
            'status_ketersediaan' => 'required|in:0,1',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos','public');
        }

        $data['user_id'] = Auth::id();
        Kos::create($data);

        return redirect()->route('kos.manage')
                         ->with('success','Kos berhasil ditambahkan.');
    }

    /**
     * 6. Form edit (hanya milik sendiri)
     */
    public function edit($id_kos)
    {
        $kos = Kos::where('user_id', Auth::id())
                  ->findOrFail($id_kos);
        return view('kos.edit', compact('kos'));
    }

    /**
     * 7. Update (hanya milik sendiri)
     */
    public function update(Request $request, $id_kos)
    {
        $kos = Kos::where('user_id', Auth::id())
                  ->findOrFail($id_kos);

        $data = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'deskripsi'=> 'required|string',
            'alamat'   => 'required|string|max:255',
            'harga'    => 'required|numeric',
            'fasilitas'=> 'required|string',
            'foto'     => 'nullable|image|max:2048',
            'status_ketersediaan' => 'required|in:0,1',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos','public');
        }

        $kos->update($data);

        return redirect()->route('kos.manage')
                         ->with('success','Data kos berhasil diperbarui!');
    }

    /**
     * 8. Hapus (hanya milik sendiri)
     */
    public function destroy($id_kos)
    {
        $kos = Kos::where('user_id', Auth::id())
                  ->findOrFail($id_kos);
        $kos->delete();

        return redirect()->route('kos.manage')
                         ->with('success','Kos berhasil dihapus!');
    }
}
