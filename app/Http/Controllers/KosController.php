<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosView;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\LocationHelper;

class KosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'search', 'popular']);
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Kos::where('user_id', $userId);

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_kos', 'like', "%{$kw}%")
                    ->orWhere('alamat', 'like', "%{$kw}%")
            );
        }

        $sort = $request->input('sort');
        $query->orderBy('created_at', $sort === 'terlama' ? 'asc' : 'desc');

        $kosList = $query->get();
        $totalKos = Kos::where('user_id', $userId)->count();
        $totalPenghuni = DB::table('penghuni')
            ->whereIn('kos_id', function ($q) use ($userId) {
                $q->select('id')->from('kos')->where('user_id', $userId);
            })->count();

        return view('kos.index', compact('kosList', 'totalKos', 'totalPenghuni'));
    }

    public function manage(Request $request)
    {
        return $this->index($request);
    }

    public function popular()
    {
        $popularKos = Kos::orderBy('views', 'desc')->take(10)->get();
        return view('kos.popular', compact('popularKos'));
    }

    public function show($id)
    {
        try {
            $kos = Kos::with(['user', 'reviews.user'])->findOrFail($id);
            $kos->increment('views');

            $existingChatRoom = null;
            if (Auth::check()) {
                $existingChatRoom = ChatRoom::where('kos_id', $id)
                    ->where('tenant_id', Auth::id())
                    ->where('owner_id', $kos->user_id)
                    ->first();
            }

            return view('kos.show', compact('kos', 'existingChatRoom'));
        } catch (\Exception $e) {
            return redirect()->route('kos.index')->with('error', 'Kos tidak ditemukan.');
        }
    }

    public function search(Request $request)
    {
        $raw = Kos::pluck('fasilitas')->toArray();
        $set = [];
        foreach ($raw as $csv) {
            foreach (explode(',', $csv) as $name) {
                $n = trim($name);
                if ($n && !isset($set[$n])) {
                    $set[$n] = true;
                }
            }
        }
        $facilities = array_map(fn($n) => (object)['id' => $n, 'name' => $n], array_keys($set));

        $query = Kos::query();

        if ($request->filled('search')) {
            $kw = $request->search;
            $query->where(fn($q) =>
                $q->where('nama_kos', 'like', "%{$kw}%")
                    ->orWhere('fasilitas', 'like', "%{$kw}%")
            );
        }

        if ($request->filled('location')) {
            $query->where('alamat', 'like', "%" . $request->location . "%");
        }

        $min = $request->input('price_min', 0);
        $max = $request->input('price_max', 0);
        if ($min <= $max && ($min > 0 || $max > 0)) {
            $query->whereBetween('harga', [(float)$min, (float)$max]);
        }

        if ($request->filled('facilities')) {
            $query->where(function ($q) use ($request) {
                foreach ((array)$request->facilities as $fac) {
                    $q->orWhere('fasilitas', 'like', "%{$fac}%");
                }
            });
        }

        $kosList = $query->paginate(12)->withQueryString();
        return view('kos.search', compact('kosList', 'facilities'));
    }

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
            'nama_kos' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jenis_kos' => 'required|in:putra,putri,campur',
            'durasi_sewa' => 'required|in:bulanan,tahunan',
        ]);

        $koordinat = LocationHelper::geocodeAddress($data['alamat']);
        $data['latitude'] = $koordinat['latitude'];
        $data['longitude'] = $koordinat['longitude'];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos', 'public');
        }

        $data['user_id'] = Auth::id();
        Kos::create($data);

        return redirect()->route('kos.create')->with('success', 'Kos berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kos = Kos::where('user_id', Auth::id())->findOrFail($id);
        return view('kos.edit', compact('kos'));
    }

    public function update(Request $request, $id)
    {
        $kos = Kos::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'nama_kos' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jenis_kos' => 'required|in:putra,putri,campur',
            'durasi_sewa' => 'required|in:bulanan,tahunan',
        ]);

        $koordinat = LocationHelper::geocodeAddress($data['alamat']);
        $data['latitude'] = $koordinat['latitude'];
        $data['longitude'] = $koordinat['longitude'];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_kos', 'public');
        }

        $kos->update($data);
        return redirect()->route('kos.index')->with('success', 'Kos berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kos = Kos::where('user_id', Auth::id())->findOrFail($id);
        $kos->delete();

        return redirect()->route('kos.index')->with('success', 'Kos berhasil dihapus.');
    }

    // Halaman eksplorasi kos untuk penyewa
    public function explore()
    {
        $kosList = Kos::latest()->get();
        return view('kos.explore', compact('kosList'));
    }
}
