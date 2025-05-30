<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\Kos; // Tambahkan ini
use Illuminate\Http\Request;
use App\Notifications\NewChatMessageNotification;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar chat room pengguna (inbox).
     * PBI: #12 (sebagian, untuk penyewa), dan untuk pemilik juga.
     */
    public function index()
    {
        $user = Auth::user();
        // Ambil chat rooms dimana user adalah tenant ATAU owner
        // Urutkan berdasarkan aktivitas terakhir (updated_at di chat_rooms atau timestamp pesan terakhir)
        $chatRooms = ChatRoom::where('tenant_id', $user->id)
                            ->orWhere('owner_id', $user->id)
                            ->with(['kos', 'latestMessage.sender', 'tenant', 'owner']) // Eager load relasi
                            ->orderByDesc(
                                Message::select('created_at')
                                    ->whereColumn('chat_room_id', 'chat_rooms.id')
                                    ->latest()
                                    ->take(1)
                            ) // Urutkan berdasarkan pesan terakhir
                            ->latest('chat_rooms.updated_at') // Fallback jika tidak ada pesan
                            ->paginate(15);


        return view('chat.index', compact('chatRooms', 'user'));
    }

    /**
     * Menampilkan pesan dalam satu chat room.
     * PBI: #12 (sebagian, untuk penyewa)
     */
    public function show(ChatRoom $chatRoom)
    {
        // Pastikan user yang login adalah bagian dari chat room ini
        if (Auth::id() !== $chatRoom->tenant_id && Auth::id() !== $chatRoom->owner_id) {
            abort(403, 'Anda tidak memiliki akses ke ruang chat ini.');
        }

        // Tandai pesan yang diterima oleh user saat ini sebagai sudah dibaca
        $chatRoom->messages()
                 ->where('sender_id', '!=', Auth::id())
                 ->whereNull('read_at')
                 ->update(['read_at' => now()]);

        $messages = $chatRoom->messages()->with('sender')->get();
        $currentUser = Auth::user();

        return view('chat.show', compact('chatRoom', 'messages', 'currentUser'));
    }

    /**
     * Mengirim pesan baru.
     * PBI: #10 (untuk penyewa), dan untuk pemilik juga.
     */
    public function store(Request $request, ChatRoom $chatRoom)
    {
        // Pastikan user yang login adalah bagian dari chat room ini (penyewa atau pemilik)
        if (Auth::id() !== $chatRoom->tenant_id && Auth::id() !== $chatRoom->owner_id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Akses ditolak.'], 403);
            }
            abort(403, 'Anda tidak dapat mengirim pesan ke ruang chat ini.');
        }

        $request->validate([
            'body' => 'required|string|max:5000', // Max 5000 karakter, sesuaikan jika perlu
        ]);

        $currentUser = Auth::user(); 

        $message = $chatRoom->messages()->create([
            'sender_id' => $currentUser->id, 
            'body' => $request->body,
            // 'read_at' akan null secara default, menandakan belum dibaca
        ]);

        // Update `updated_at` di chat_room untuk sorting inbox dan notifikasi real-time jika ada
        $chatRoom->touchLastActivity(); // Pastikan method ini ada di model ChatRoom

        // Untuk PBI #10, fokusnya adalah pesan terkirim.
        // Notifikasi untuk pemilik (PBI #11) akan ditangani terpisah atau sebagai langkah selanjutnya.
        $recipient = null;
        if ($chatRoom->owner_id == $currentUser->id) {
            // Jika pengirim adalah pemilik, penerima adalah penyewa
            $recipient = $chatRoom->tenant; // Asumsi relasi tenant() ada di model ChatRoom
        } elseif ($chatRoom->tenant_id == $currentUser->id) {
            // Jika pengirim adalah penyewa, penerima adalah pemilik
            $recipient = $chatRoom->owner; // Asumsi relasi owner() ada di model ChatRoom
        }

        if ($recipient) { // Pastikan $recipient tidak null (ada penerima yang valid)
            // Kirim notifikasi HANYA jika penerima bukan pengirim pesan itu sendiri
            if ($recipient->id != $currentUser->id) {
                // $currentUser di sini adalah pengirim pesan
                $recipient->notify(new NewChatMessageNotification($message, $chatRoom, $currentUser));
            }
        }

        if ($request->expectsJson()) {
            // Jika request AJAX, kirim response JSON dengan pesan yang baru dibuat
            // Eager load sender untuk ditampilkan di UI jika menggunakan AJAX append
            return response()->json(['success' => true, 'message' => $message->load('sender')]);
        }

        // Jika request biasa (non-AJAX), redirect kembali ke halaman chat
        return redirect()->route('chat.show', $chatRoom->id);
    }

    /**
     * Mengambil jumlah total pesan yang belum dibaca untuk pengguna saat ini.
     */
    public function getNotificationCount(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'error' => 'Unauthenticated'], 401);
        }

        $userId = Auth::id();

        // Hitung pesan yang belum dibaca di semua chat room dimana user terlibat
        // Pesan tersebut harus dikirim oleh orang lain dan belum memiliki timestamp read_at
        $unreadCount = Message::whereHas('chatRoom', function ($query) use ($userId) {
                                $query->where('tenant_id', $userId)
                                    ->orWhere('owner_id', $userId);
                            })
                            ->where('sender_id', '!=', $userId) // Pesan dari orang lain
                            ->whereNull('read_at')             // Yang belum dibaca oleh penerima (user saat ini)
                            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $unreadCount,
            ],
        ]);
    }
    /**
     * Fungsi untuk inisiasi chat dari KosController sudah bagus,
     * tapi kita bisa pindahkan atau buat versi API-nya di sini jika perlu.
     * Untuk saat ini, kita gunakan yang sudah ada di KosController.
     * Route initiateChat di KosController mengarah ke route('chat.room', $chatRoom->id)
     * yang mana adalah 'chat.show' di controller ini.
     */
}
