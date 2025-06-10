<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Favorite;
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
        $userRole = Auth::user()->role;
        $query = Kos::where('user_id', $userId);

        if ($userRole != 'pemilik' && $userRole != 'admin') {
            return redirect()->route('redirect', ['error' => 'Akses ditolak.']);
        }
        
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
        $kos = Kos::with(['user', 'reviews.user'])->findOrFail($id);
        $kos->increment('views');
        $kosFavorites = Favorite::where('kos_id', $id)
            ->where('user_id', Auth::id())
            ->exists();
        $hasFavorited = $kosFavorites;

        $existingChatRoom = null;
        if (Auth::check()) {
            $existingChatRoom = ChatRoom::where('kos_id', $id)
                ->where('tenant_id', Auth::id())
                ->where('owner_id', $kos->user_id)
                ->first();
        }

        return view('kos.show', compact('kos', 'existingChatRoom', 'hasFavorited'));
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

    public function initiateChatWithOwner($kosId)
    {
        try {
            $kos = Kos::findOrFail($kosId);
            
            if (!Auth::check()) {
                return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk menghubungi pemilik kos.');
            }

            $tenantId = Auth::id();
            $ownerId = $kos->user_id; // Atau $kos->pemilik->id jika relasi sudah diubah dan itu yang benar

            if ($tenantId == $ownerId) {
                return redirect()->back()->with('error', 'Anda tidak dapat mengirim pesan ke diri sendiri.');
            }

            // Cari atau buat ChatRoom
            $chatRoom = ChatRoom::firstOrCreate(
                [
                    'kos_id' => $kosId,
                    'tenant_id' => $tenantId,
                    'owner_id' => $ownerId
                ]
                // Tidak perlu array kedua jika hanya ingin mencari atau membuat dengan parameter di atas
            );

            // Arahkan ke halaman chat (pastikan nama route konsisten)
            return redirect()->route('chat.show', $chatRoom->id) 
                            ->with('success', 'Chat room berhasil dibuka. Anda dapat mulai mengirim pesan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Kos tidak ditemukan.');
        } catch (\Exception $e) {
            // Log error $e->getMessage()
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuka chat: ' . $e->getMessage());
        }
    }

    /**
     * Get detail kos dengan informasi chat
     */

    /**
     * Get kos yang sedang dalam proses chat (untuk pemilik kos)
     */
    public function getKosWithActiveChats()
    {
        try {
            $ownerId = Auth::id();
            
            $kosWithChats = Kos::where('user_id', $ownerId)
                              ->whereHas('chatRooms')
                              ->with(['chatRooms' => function($query) {
                                  $query->with(['tenant', 'latestMessage'])
                                        ->orderBy('updated_at', 'desc');
                              }])
                              ->get();

            return response()->json([
                'success' => true,
                'data' => $kosWithChats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kos dengan chat aktif'
            ], 500);
        }
    }

    /**
     * Get statistik chat untuk dashboard pemilik kos
     */
    public function getChatStatistics()
    {
        try {
            $ownerId = Auth::id();
            
            $totalChatRooms = ChatRoom::where('owner_id', $ownerId)->count();
            $activeChatRooms = ChatRoom::where('owner_id', $ownerId)
                                     ->where('updated_at', '>=', now()->subDays(30))
                                     ->count();
            $unreadMessages = \App\Models\Message::whereHas('chatRoom', function($query) use ($ownerId) {
                                                     $query->where('owner_id', $ownerId);
                                                 })
                                                 ->where('sender_id', '!=', $ownerId)
                                                 ->where('is_read', false)
                                                 ->count();

            $mostInquiredKos = Kos::where('user_id', $ownerId)
                                 ->withCount('chatRooms')
                                 ->orderBy('chat_rooms_count', 'desc')
                                 ->limit(5)
                                 ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_chat_rooms' => $totalChatRooms,
                    'active_chat_rooms' => $activeChatRooms,
                    'unread_messages' => $unreadMessages,
                    'most_inquired_kos' => $mostInquiredKos
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik chat'
            ], 500);
        }
    }
}
