<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KosController extends Controller
{
    public function __construct()
    {
        // everything except index/show/search requires login
        $this->middleware('auth')->except(['index','show','search']);
    }

    /**
     * Public landing + simple keyword filter
     */
    public function index(Request $request)
    {
        $query = Kos::query();

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(function($q) use ($kw) {
                $q->where('nama_kos','like',"%{$kw}%")
                  ->orWhere('alamat','like',"%{$kw}%")
                  ->orWhere('fasilitas','like',"%{$kw}%");
            });
        }

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('harga', [
                $request->price_min, $request->price_max
            ]);
        }

        if ($request->filled('fasilitas')) {
            $query->where('fasilitas','like','%'.$request->fasilitas.'%');
        }

        $kosList = $query->paginate(12)->withQueryString();
        return view('home.dashboard', compact('kosList'));
    }

    /**
     * Public: full filter + search page
     */
    public function search(Request $request)
    {
        // 1) build a unique list of facility names from your CSV column
        $raw = Kos::pluck('fasilitas')->toArray();
        $set = [];
        foreach ($raw as $csv) {
            foreach (explode(',', $csv) as $name) {
                $n = trim($name);
                if ($n !== '' && ! isset($set[$n])) {
                    $set[$n] = true;
                }
            }
        }
        // turn into simple [ { id: name, name: name }, … ]
        $facilities = [];
        foreach (array_keys($set) as $name) {
            $facilities[] = (object)[ 'id' => $name, 'name' => $name ];
        }

        // 2) build the query
        $query = Kos::query();

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(function($q) use ($kw) {
                $q->where('nama_kos','like',"%{$kw}%")
                  ->orWhere('alamat','like',"%{$kw}%")
                  ->orWhere('fasilitas','like',"%{$kw}%");
            });
        }

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('harga', [
                $request->price_min, $request->price_max
            ]);
        }

        if ($request->filled('facilities')) {
            $query->where(function($q) use ($request) {
                foreach ((array)$request->facilities as $fac) {
                    $q->orWhere('fasilitas','like',"%{$fac}%");
                }
            });
        }

        // optional: geolocation + radius (needs latitude & longitude columns)
        if ($request->filled('loc_lat','loc_lng','radius')) {
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

        $kosList = $query->paginate(12)->withQueryString();
        return view('kos.search', compact('kosList','facilities'));
    }

    /**
     * Public: single kos detail
     */
    public function show($id_kos)
    {
        $kos = Kos::findOrFail($id_kos);
        return view('kos.show', compact('kos'));
    }

    /**
     * Authenticated user only: manage listing
     */
    public function manage()
    {
        $kosList = Kos::where('user_id',Auth::id())
                      ->latest()
                      ->paginate(10);
        return view('kos.manage', compact('kosList'));
    }

    /**
     * Authenticated: form to create
     */
    public function create()
    {
        return view('kos.create');
    }

    /**
     * Authenticated: store new kos
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kos'            => 'required|string|max:255',
            'deskripsi'           => 'required|string',
            'alamat'              => 'required|string|max:255',
            'harga'               => 'required|numeric',
            'fasilitas'           => 'required|string',
            'foto'                => 'nullable|image|max:2048',
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
     * Authenticated: form to edit
     */
    public function edit($id_kos)
    {
        $kos = Kos::where('user_id',Auth::id())
                  ->findOrFail($id_kos);
        return view('kos.edit', compact('kos'));
    }

    /**
     * Authenticated: update existing kos
     */
    public function update(Request $request, $id_kos)
    {
        $kos = Kos::where('user_id',Auth::id())
                  ->findOrFail($id_kos);

        $data = $request->validate([
            'nama_kos'            => 'required|string|max:255',
            'deskripsi'           => 'required|string',
            'alamat'              => 'required|string|max:255',
            'harga'               => 'required|numeric',
            'fasilitas'           => 'required|string',
            'foto'                => 'nullable|image|max:2048',
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
     * Authenticated: delete kos
     */
    public function destroy($id_kos)
    {
        $kos = Kos::where('user_id',Auth::id())
                  ->findOrFail($id_kos);
        $kos->delete();

        return redirect()->route('kos.manage')
                         ->with('success','Kos berhasil dihapus!');
    }
}
