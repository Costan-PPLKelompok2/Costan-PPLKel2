<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\Kos;
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
     */
    public function index()
    {
        $user = Auth::user();
        $chatRooms = ChatRoom::where('tenant_id', $user->id)
            ->orWhere('owner_id', $user->id)
            ->with(['kos', 'latestMessage.sender', 'tenant', 'owner'])
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('chat_room_id', 'chat_rooms.id')
                    ->latest()
                    ->take(1)
            )
            ->latest('chat_rooms.updated_at')
            ->paginate(15);

        return view('chat.index', compact('chatRooms', 'user'));
    }

    /**
     * Menampilkan pesan dalam satu chat room.
     */
    public function show(ChatRoom $chatRoom)
    {
        if (Auth::id() !== $chatRoom->tenant_id && Auth::id() !== $chatRoom->owner_id) {
            abort(403, 'Anda tidak memiliki akses ke ruang chat ini.');
        }

        $chatRoom->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $chatRoom->messages()->with('sender')->get(); // Tidak perlu ->get() jika sudah di-load di view
        $currentUser = Auth::user();

        return view('chat.show', compact('chatRoom', 'messages', 'currentUser'));
    }

    /**
     * Mengirim pesan baru.
     */
    public function store(Request $request, ChatRoom $chatRoom)
    {
        if (Auth::id() !== $chatRoom->tenant_id && Auth::id() !== $chatRoom->owner_id) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Akses ditolak.'], 403);
            }
            abort(403, 'Anda tidak dapat mengirim pesan ke ruang chat ini.');
        }

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $currentUser = Auth::user();

        $message = $chatRoom->messages()->create([
            'sender_id' => $currentUser->id,
            'body' => $request->body,
        ]);

        $chatRoom->touchLastActivity();

        $recipient = null;
        if ($chatRoom->owner_id == $currentUser->id) {
            $recipient = $chatRoom->tenant;
        } elseif ($chatRoom->tenant_id == $currentUser->id) {
            $recipient = $chatRoom->owner;
        }

        if ($recipient && $recipient->id != $currentUser->id) {
            $recipient->notify(new NewChatMessageNotification($message, $chatRoom, $currentUser));
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message->load('sender')]);
        }

        return redirect()->route('chat.show', $chatRoom->id);
    }

    /**
     * Update an existing chat message.
     */
    public function updateMessage(Request $request, Message $message)
    {
        if (!$message->canBeModifiedBy(Auth::user())) {
            return response()->json(['success' => false, 'error' => 'Anda tidak diizinkan mengedit pesan ini.'], 403);
        }

        // Batas waktu edit (misal: 15 menit)
        if ($message->created_at->diffInMinutes(now()) > 15) {
             return response()->json(['success' => false, 'error' => 'Batas waktu untuk mengedit pesan telah terlewat.'], 422);
        }

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $message->body = $request->body;
        $message->edited_at = now(); // Tandai sebagai diedit
        $message->save();

        return response()->json(['success' => true, 'message' => $message->load('sender')]);
    }

    /**
     * Delete a chat message.
     * (Soft delete jika model Message menggunakan trait SoftDeletes)
     */
    public function destroyMessage(Message $message)
    {
        // Asumsi Anda memiliki method canBeModifiedBy di model Message
        // Jika tidak, Anda bisa menggunakan: if ($message->sender_id !== Auth::id())
        if (!$message->canBeModifiedBy(Auth::user())) {
            return response()->json(['success' => false, 'error' => 'Anda tidak diizinkan menghapus pesan ini.'], 403);
        }

        if ($message->delete()) { // Menggunakan delete() standar, atau softDeletes() jika trait digunakan
            return response()->json(['success' => true, 'message_id' => $message->id, 'info' => 'Pesan berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'error' => 'Gagal menghapus pesan.'], 500);
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
        $unreadCount = Message::whereHas('chatRoom', function ($query) use ($userId) {
            $query->where('tenant_id', $userId)
                ->orWhere('owner_id', $userId);
        })
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $unreadCount,
            ],
        ]);
    }
}