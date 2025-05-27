<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User_profile;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $chatRooms = ChatRoom::where('tenant_id', $userId)
                            ->orWhere('owner_id', $userId)
                            ->with(['kos', 'tenant', 'owner'])
                            ->withCount(['messages as unread_count' => function($query) use ($userId) {
                                $query->where('sender_id', '!=', $userId)
                                    ->where('is_read', false);
                            }])
                            ->with(['messages' => function($query) {
                                $query->latest()->limit(1); // ambil pesan terakhir
                            }])
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return view('chat.index', compact('chatRooms'));
    }

    public function show($chatRoomId)
    {
        $userId = Auth::id();

        // Ambil chat room dan pastikan user punya akses
        $chatRoom = ChatRoom::with([
                            'messages.sender.user_profile', 
                            'tenant.user_profile', 
                            'owner.user_profile', 
                            'kos'
                        ])
                        ->where('id', $chatRoomId)
                        ->where(function ($query) use ($userId) {
                            $query->where('tenant_id', $userId)
                                ->orWhere('owner_id', $userId);
                        })
                        ->firstOrFail();

        // Tandai pesan sebagai dibaca
        Message::where('chat_room_id', $chatRoomId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.room', compact('chatRoom'));
    }


    /**
     * Membuat atau mendapatkan chat room antara penyewa dan pemilik kos
     */
    public function getOrCreateChatRoom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kos_id' => 'required|exists:kos,id',
            'owner_id' => 'required|exists:users_profile,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $tenantId = Auth::id();
        $kosId = $request->kos_id;
        $ownerId = $request->owner_id;

        // Cek apakah chat room sudah ada
        $chatRoom = ChatRoom::where('kos_id', $kosId)
                           ->where('tenant_id', $tenantId)
                           ->where('owner_id', $ownerId)
                           ->first();

        if (!$chatRoom) {
            // Buat chat room baru
            $chatRoom = ChatRoom::create([
                'kos_id' => $kosId,
                'tenant_id' => $tenantId,
                'owner_id' => $ownerId
            ]);
        }

        // Load relasi
        $chatRoom->load(['kos', 'tenant', 'owner']);

        return response()->json([
            'success' => true,
            'data' => $chatRoom,
            'message' => 'Chat room ready'
        ]);
    }

    /**
     * Mengirim pesan dalam chat room
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_room_id' => 'required|exists:chat_rooms,id',
            'message' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $senderId = Auth::id();
        $chatRoomId = $request->chat_room_id;

        // Verifikasi bahwa user adalah bagian dari chat room ini
        $chatRoom = ChatRoom::where('id', $chatRoomId)
                           ->where(function($query) use ($senderId) {
                               $query->where('tenant_id', $senderId)
                                     ->orWhere('owner_id', $senderId);
                           })
                           ->first();

        if (!$chatRoom) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to chat room'
            ], 403);
        }

        // Buat pesan baru
        $message = Message::create([
            'chat_room_id' => $chatRoomId,
            'sender_id' => $senderId,
            'message' => $request->message,
            'is_read' => false
        ]);

        // Load relasi sender
        $message->load('sender');

        // Update timestamp chat room
        $chatRoom->touch();

        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => 'Message sent successfully'
        ]);
    }

    /**
     * Mendapatkan semua pesan dalam chat room
     */
    public function getMessages($chatRoomId)
    {
        $userId = Auth::id();

        // Verifikasi akses ke chat room
        $chatRoom = ChatRoom::where('id', $chatRoomId)
                           ->where(function($query) use ($userId) {
                               $query->where('tenant_id', $userId)
                                     ->orWhere('owner_id', $userId);
                           })
                           ->first();

        if (!$chatRoom) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room not found or unauthorized'
            ], 404);
        }

        // Ambil semua pesan
        $messages = Message::where('chat_room_id', $chatRoomId)
                          ->with('sender')
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Mark pesan sebagai dibaca (yang bukan dari sender current user)
        Message::where('chat_room_id', $chatRoomId)
               ->where('sender_id', '!=', $userId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => [
                'chat_room' => $chatRoom->load(['kos', 'tenant', 'owner']),
                'messages' => $messages
            ]
        ]);
    }

    /**
     * Mendapatkan semua chat rooms untuk user
     */
    public function getUserChatRooms($userId = null)
    {
        $userId = $userId ?? Auth::id();

        $chatRooms = ChatRoom::where('tenant_id', $userId)
                            ->orWhere('owner_id', $userId)
                            ->with(['kos', 'tenant', 'owner'])
                            ->withCount(['messages as unread_count' => function($query) use ($userId) {
                                $query->where('sender_id', '!=', $userId)
                                      ->where('is_read', false);
                            }])
                            ->with(['messages' => function($query) {
                                $query->latest()->limit(1);
                            }])
                            ->orderBy('updated_at', 'desc')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $chatRooms
        ]);
    }

    /**
     * Mark pesan sebagai dibaca
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required|exists:messages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = Auth::id();
        $messageId = $request->message_id;

        // Cek apakah user berhak mark message ini sebagai read
        $message = Message::whereHas('chatRoom', function($query) use ($userId) {
                              $query->where('tenant_id', $userId)
                                    ->orWhere('owner_id', $userId);
                          })
                          ->where('id', $messageId)
                          ->where('sender_id', '!=', $userId)
                          ->first();

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found or unauthorized'
            ], 404);
        }

        $message->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);
    }

    /**
     * Mark semua pesan dalam chat room sebagai dibaca
     */
    public function markChatRoomAsRead($chatRoomId)
    {
        $userId = Auth::id();

        // Verifikasi akses ke chat room
        $chatRoom = ChatRoom::where('id', $chatRoomId)
                           ->where(function($query) use ($userId) {
                               $query->where('tenant_id', $userId)
                                     ->orWhere('owner_id', $userId);
                           })
                           ->first();

        if (!$chatRoom) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room not found or unauthorized'
            ], 404);
        }

        // Update semua pesan yang belum dibaca dari lawan chat
        Message::where('chat_room_id', $chatRoomId)
               ->where('sender_id', '!=', $userId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All messages marked as read'
        ]);
    }

    /**
     * Hapus chat room (soft delete atau hard delete)
     */
    public function deleteChatRoom($chatRoomId)
    {
        $userId = Auth::id();

        $chatRoom = ChatRoom::where('id', $chatRoomId)
                           ->where(function($query) use ($userId) {
                               $query->where('tenant_id', $userId)
                                     ->orWhere('owner_id', $userId);
                           })
                           ->first();

        if (!$chatRoom) {
            return response()->json([
                'success' => false,
                'message' => 'Chat room not found or unauthorized'
            ], 404);
        }

        // Hapus chat room dan semua pesannya
        $chatRoom->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat room deleted successfully'
        ]);
    }

    /**
     * Get notification count untuk user
     */
    public function getNotificationCount($userId = null)
    {
        $userId = $userId ?? Auth::id();

        $unreadCount = Message::whereHas('chatRoom', function($query) use ($userId) {
                                  $query->where('tenant_id', $userId)
                                        ->orWhere('owner_id', $userId);
                              })
                              ->where('sender_id', '!=', $userId)
                              ->where('is_read', false)
                              ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $unreadCount
            ]
        ]);
    }
}