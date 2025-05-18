<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KosController extends Controller
{
    public function __construct()
    {
        // everything except viewing/searching/popular requires login
        $this->middleware('auth')->except(['show', 'search', 'popular']);
    }

    /**
     * Owner dashboard: list your kos + stats (+ optional search & sort)
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query  = Kos::where('user_id', $userId);

        // optional keyword search
        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_kos', 'like', "%{$kw}%")
                  ->orWhere('alamat',   'like', "%{$kw}%")
            );
        }

        // optional sort
        $sort = $request->input('sort');
        $query->orderBy(
            'created_at',
            $sort === 'terlama' ? 'asc' : 'desc'
        );

        $kosList = $query->get();

        // stats
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
     * Alias for index (dashboard/manage)
     */
    public function manage(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Top 10 most viewed kos
     */
    public function popular()
    {
        $popularKos = Kos::orderBy('views', 'desc')
                         ->take(10)
                         ->get();

        return view('kos.popular', compact('popularKos'));
    }

    /**
     * Public detail page; increments view count
     */
    public function show($id)
    {
        $kos = Kos::findOrFail($id);
        $kos->increment('views');
        return view('kos.show', compact('kos'));
    }

    /**
     * Full‐filter search page
     */
    public function search(Request $request)
    {
        // build unique list of facility names from CSV
        $raw = Kos::pluck('fasilitas')->toArray();
        $set = [];
        foreach ($raw as $csv) {
            foreach (explode(',', $csv) as $name) {
                $n = trim($name);
                if ($n !== '' && !isset($set[$n])) {
                    $set[$n] = true;
                }
            }
        }
        $facilities = array_map(fn($n) => (object)['id' => $n, 'name' => $n], array_keys($set));

        $query = Kos::query();

        // keyword
        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_kos', 'like', "%{$kw}%")
                  ->orWhere('alamat',   'like', "%{$kw}%")
                  ->orWhere('fasilitas','like', "%{$kw}%")
            );
        }

        // price range (only if min ≤ max)
        if ($request->filled('price_min','price_max')) {
            $min = $request->price_min;
            $max = $request->price_max;
            if ($min <= $max) {
                $query->whereBetween('harga', [$min, $max]);
            }
        }

        // multi‐select facilities
        if ($request->filled('facilities')) {
            $query->where(function($q) use ($request) {
                foreach ((array)$request->facilities as $fac) {
                    $q->orWhere('fasilitas', 'like', "%{$fac}%");
                }
            });
        }

        // geolocation + radius (requires latitude & longitude columns)
        if ($request->filled('loc_lat','loc_lng','radius')) {
            $lat = $request->loc_lat;
            $lng = $request->loc_lng;
            $radius = (float)$request->radius;
            $haversine = "(6371 * acos(
                cos(radians(?))
              * cos(radians(latitude))
              * cos(radians(longitude) - radians(?))
              + sin(radians(?))
              * sin(radians(latitude))
            ))";
            $query->selectRaw("kos.*, {$haversine} AS distance", [$lat,$lng,$lat])
                  ->having('distance', '<=', $radius)
                  ->orderBy('distance');
        }

        $kosList = $query->paginate(12)->withQueryString();

        return view('kos.search', compact('kosList', 'facilities'));
    }

    /**
     * Show form to create a new kos
     */
    public function create()
    {
        return view('kos.create');
    }

    /**
     * Store a newly created kos
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kos'             => 'required|string|max:255',
            'deskripsi'            => 'required|string',
            'alamat'               => 'required|string|max:255',
            'harga'                => 'required|numeric',
            'fasilitas'            => 'required|string',
            'foto'                 => 'nullable|image|max:2048',
            'status_ketersediaan'  => 'nullable|in:0,1',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos','public');
        }

        $data['user_id'] = Auth::id();
        Kos::create($data);

        return redirect()->route('kos.index')
                         ->with('success','Kos berhasil ditambahkan.');
    }

    /**
     * Show form to edit an existing kos
     */
    public function edit($id)
    {
        $kos = Kos::where('user_id', Auth::id())
                  ->findOrFail($id);

        return view('kos.edit', compact('kos'));
    }

    /**
     * Update the given kos
     */
    public function update(Request $request, $id)
    {
        $kos = Kos::where('user_id', Auth::id())
                  ->findOrFail($id);

        $data = $request->validate([
            'nama_kos'             => 'required|string|max:255',
            'deskripsi'            => 'required|string',
            'alamat'               => 'required|string|max:255',
            'harga'                => 'required|numeric',
            'fasilitas'            => 'required|string',
            'foto'                 => 'nullable|image|max:2048',
            'status_ketersediaan'  => 'nullable|in:0,1',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos','public');
        }

        $kos->update($data);

        return redirect()->route('kos.index')
                         ->with('success','Data kos berhasil diperbarui!');
    }

    /**
     * Delete the given kos
     */
    public function destroy($id)
    {
        $kos = Kos::where('user_id', Auth::id())
                  ->findOrFail($id);

        $kos->delete();

        return redirect()->route('kos.index')
                         ->with('success','Kos berhasil dihapus!');
    }
}
