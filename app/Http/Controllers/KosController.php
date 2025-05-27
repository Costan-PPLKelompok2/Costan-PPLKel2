<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosView;
use App\Models\ChatRoom;
use App\Models\User_profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

            return view('kos.detail', compact('kos', 'existingChatRoom'));
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

    /**
     * Inisiasi chat dengan pemilik kos dari halaman detail kos
     */
    public function initiateChatWithOwner($kosId)
    {
        try {
            $kos = Kos::findOrFail($kosId);
            
            if (!Auth::check()) {
                return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk menghubungi pemilik kos.');
            }

            $tenantId = Auth::id();
            $ownerId = $kos->user_id;

            if ($tenantId == $ownerId) {
                return redirect()->back()->with('error', 'Anda tidak dapat mengirim pesan ke diri sendiri.');
            }

            $chatRoom = ChatRoom::where('kos_id', $kosId)
                               ->where('tenant_id', $tenantId)
                               ->where('owner_id', $ownerId)
                               ->first();

            if (!$chatRoom) {
                $chatRoom = ChatRoom::create([
                    'kos_id' => $kosId,
                    'tenant_id' => $tenantId,
                    'owner_id' => $ownerId
                ]);
            }

            return redirect()->route('chat.room', $chatRoom->id)
                           ->with('success', 'Chat room berhasil dibuka.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuka chat.');
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
