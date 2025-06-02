<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\LocationHelper;

class KosController extends Controller
{
    public function __construct()
    {
        // Semua kecuali show, search, popular butuh login
        $this->middleware('auth')->except(['show', 'search', 'popular']);
    }

    /**
     * Dashboard pemilik kos
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Kos::where('user_id', $userId);

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_kos', 'like', "%{$kw}%")
                  ->orWhere('alamat',   'like', "%{$kw}%")
            );
        }

        $sort = $request->input('sort');
        $query->orderBy('created_at', $sort === 'terlama' ? 'asc' : 'desc');

        $kosList       = $query->get();
        $totalKos      = Kos::where('user_id', $userId)->count();
        $totalPenghuni = DB::table('penghuni')
            ->whereIn('kos_id', function($q) use ($userId) {
                $q->select('id')
                  ->from('kos')
                  ->where('user_id', $userId);
            })
            ->count();

        return view('kos.index', compact('kosList', 'totalKos', 'totalPenghuni'));
    }

    /**
     * Alias dashboard
     */
    public function manage(Request $request)
    {
        return $this->index($request);
    }

    /**
     * 10 kos terpopuler
     */
    public function popular()
    {
        $popularKos = Kos::orderBy('views', 'desc')
                         ->take(10)
                         ->get();

        return view('kos.popular', compact('popularKos'));
    }

    /**
     * Detail publik + increment view
     */
    public function show($id)
    {
        $kos = Kos::findOrFail($id);
        $kos->increment('views');
        return view('kos.show', compact('kos'));
    }

    /**
     * Halaman search lengkap
     */
    public function search(Request $request)
    {
        // bangun daftar fasilitas unik
        $raw = Kos::pluck('fasilitas')->toArray();
        $set = [];
        foreach ($raw as $csv) {
            foreach (explode(',', $csv) as $name) {
                $n = trim($name);
                if ($n && ! isset($set[$n])) {
                    $set[$n] = true;
                }
            }
        }
        $facilities = array_map(fn($n) => (object)['id' => $n, 'name' => $n], array_keys($set));

        $query = Kos::query();

        // 1) keyword: nama_kos atau fasilitas
        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_kos', 'like', "%{$kw}%")
                  ->orWhere('fasilitas','like', "%{$kw}%")
            );
        }

        // 2) lokasi: alamat saja
        if ($request->filled('location')) {
            $loc = $request->location;
            $query->where('alamat', 'like', "%{$loc}%");
        }

        // 3) rentang harga
        $min = $request->input('price_min', 0);
        $max = $request->input('price_max', 0);
        if ($min <= $max && ($min > 0 || $max > 0)) {
            $query->whereBetween('harga', [(float)$min, (float)$max]);
        }

        // 4) multi‐pilih fasilitas
        if ($request->filled('facilities')) {
            $query->where(function($q) use ($request) {
                foreach ((array)$request->facilities as $fac) {
                    $q->orWhere('fasilitas','like', "%{$fac}%");
                }
            });
        }

        $kosList = $query->paginate(12)->withQueryString();
        return view('kos.search', compact('kosList', 'facilities'));
    }

    /**
     * Toggle ID di session “compare”
     */
    public function toggleCompare(Request $request, $id)
    {
        $compare = session('compare', []);

        if (in_array($id, $compare)) {
            $compare = array_filter($compare, fn($item) => $item != $id);
            $request->session()->flash('status', 'Dihapus dari perbandingan');
        } else {
            $compare[] = $id;
            $request->session()->flash('status', 'Ditambahkan ke perbandingan');
        }

        session(['compare' => array_values($compare)]);
        return back();
    }

    /**
     * Halaman perbandingan side-by-side
     */
    public function comparePage()
    {
        $compare = session('compare', []);
        $kosList = Kos::whereIn('id', $compare)->get();
        return view('kos.compare', compact('kosList'));
    }

    public function create()
    {
        return view('kos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kos'            => 'required|string|max:255',
            'deskripsi'           => 'required|string',
            'alamat'              => 'required|string|max:255',
            'harga'               => 'required|numeric',
            'fasilitas'           => 'required|string',
            'foto'                => 'nullable|image|max:2048',
            'status_ketersediaan' => 'nullable|in:0,1',
        ]);

        $koordinat = LocationHelper::geocodeAddress($data['alamat']);

        $data['latitude'] = $koordinat['latitude'];
        $data['longitude'] = $koordinat['longitude'];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos','public');
        }

        $data['user_id'] = Auth::id();
        Kos::create($data);

        return redirect()->route('kos.index')->with('success','Kos berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kos = Kos::where('user_id', Auth::id())
                  ->findOrFail($id);
        return view('kos.edit', compact('kos'));
    }

    public function update(Request $request, $id)
    {
        $kos = Kos::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'nama_kos'            => 'required|string|max:255',
            'deskripsi'           => 'required|string',
            'alamat'              => 'required|string|max:255',
            'harga'               => 'required|numeric',
            'fasilitas'           => 'required|string',
            'foto'                => 'nullable|image|max:2048',
            'status_ketersediaan' => 'nullable|in:0,1',
        ]);
        
        $koordinat = LocationHelper::geocodeAddress($data['alamat']);
        
        if (!$koordinat) {
            return redirect()->back()->withErrors(['alamat' => 'Gagal mendapatkan koordinat untuk alamat yang diberikan.']);
        }
        else{
            $data['latitude'] = $koordinat['latitude'];
            $data['longitude'] = $koordinat['longitude'];
        }


        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos','public');
        }

        $kos->update($data);
        return redirect()->route('kos.index')->with('success','Data kos berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kos = Kos::where('user_id', Auth::id())->findOrFail($id);
        $kos->delete();
        return redirect()->route('kos.index')->with('success','Kos berhasil dihapus!');
    }
}
