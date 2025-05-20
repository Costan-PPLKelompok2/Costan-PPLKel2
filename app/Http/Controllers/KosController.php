<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\LocationHelper;

class KosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search', 'popular']);
    }
    
    /**
     * Public landing + simple keyword filter
     */
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

    /**
     * Statistik dan manajemen kos (dashboard pemilik)
     */
    public function manage(Request $request)
    {
        $user_id = auth()->id();
        $kosList = Kos::where('user_id', $user_id);

        $totalKos = $kosList->count();
        $totalPenghuni = DB::table('penghuni') // sesuaikan nama tabel
            ->whereIn('kos_id', function ($query) use ($user_id) {
                $query->select('id')
                    ->from('kos')
                    ->where('user_id', $user_id);
            })->count();

        if ($request->filled('search')) {
            $search = $request->search;
            $kosList->where(function ($q) use ($search) {
                $q->where('nama_kos', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort');
        if ($sort === 'terbaru') {
            $kosList->orderBy('created_at', 'desc');
        } elseif ($sort === 'terlama') {
            $kosList->orderBy('created_at', 'asc');
        } else {
            $kosList->orderBy('created_at', 'desc');
        }

        $kosList = $kosList->get();

        return view('kos.index', compact('kosList', 'totalKos', 'totalPenghuni'));
    }

    public function popular()
    {
        $popularKos = Kos::orderBy('views', 'desc')->take(10)->get();
        return view('popular', compact('popularKos'));
    }

    public function show($id)
    {
        $kos = Kos::findOrFail($id);
        $kos->increment('views');
        return view('kos.show', compact('kos'));
    }

    public function search(Request $request)
    {
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
        $facilities = [];
        foreach (array_keys($set) as $name) {
            $facilities[] = (object)['id' => $name, 'name' => $name];
        }

        $query = Kos::query();

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(function ($q) use ($kw) {
                $q->where('nama_kos', 'like', "%{$kw}%")
                    ->orWhere('alamat', 'like', "%{$kw}%")
                    ->orWhere('fasilitas', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('harga', [
                $request->price_min, $request->price_max
            ]);
        }

        if ($request->filled('facilities')) {
            $query->where(function ($q) use ($request) {
                foreach ((array)$request->facilities as $fac) {
                    $q->orWhere('fasilitas', 'like', "%{$fac}%");
                }
            });
        }

        if ($request->filled('loc_lat', 'loc_lng', 'radius')) {
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
            $query->selectRaw("kos.*, {$haversine} AS distance", [$lat, $lng, $lat])
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        }

        $kosList = $query->paginate(12)->withQueryString();
        return view('kos.search', compact('kosList', 'facilities'));
    }

    public function create()
    {
        return view('kos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|max:2048',
            'status_ketersediaan' => 'nullable|in:0,1',
        ]);

        $koordinat = LocationHelper::geocodeAddress($data['alamat']);

        if ($koordinat) {
            $data['latitude'] = $koordinat['latitude'];
            $data['longitude'] = $koordinat['longitude'];
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos', 'public');
        }

        $data['user_id'] = Auth::id();
        Kos::create($data);

        return redirect()->route('kos.index')->with('success', 'Kos berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kos = Kos::where('user_id', auth()->id())->findOrFail($id);
        return view('kos.edit', compact('kos'));
    }

    public function update(Request $request, $id)
    {
        $kos = Kos::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|max:2048',
            'status_ketersediaan' => 'nullable|in:0,1',
        ]);
        
        $koordinat = LocationHelper::geocodeAddress($data['alamat']);

        if ($koordinat) {
            $data['latitude'] = $koordinat['latitude'];
            $data['longitude'] = $koordinat['longitude'];
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos', 'public');
        }

        $kos->update($data);
        return redirect()->route('kos.manage')->with('success', 'Data kos berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kos = Kos::where('user_id', auth()->id())->findOrFail($id);
        $kos->delete();

        return redirect()->route('kos.manage')->with('success', 'Kos berhasil dihapus!');
    }
}
