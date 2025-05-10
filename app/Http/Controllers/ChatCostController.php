<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Message;
use App\Models\User_profile;
use App\Notifications\NewMessageNotification;

class ChatCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kosts = Kost::all();
        
        $userProfileId = $request->query('user_profile_id');
        if ($userProfileId) {
            $user_profile = User_profile::find($userProfileId);
            if (!$user_profile) {
                return redirect()->route('chat.index')->withErrors('User tidak ditemukan.');
            }
            
            return view('chat.index', compact('kosts', 'user_profile'));
        }
        
        return view('chat.index', compact('kosts'));
    }

    /**
     * Display chat with specific kost and messages.
     */
    public function show(Request $request, Kost $kost)
    {
        // Ambil ID user aktif dari request (misal dari query param atau session)
        $userProfileId = $request->input('user_profile_id');

        if (!$userProfileId) {
            return redirect()->back()->withErrors('User belum dipilih.');
        }

        $users_profile = User_profile::find($userProfileId);

        if (!$users_profile) {
            return redirect()->back()->withErrors('User tidak ditemukan.');
        }

        // Ambil pesan antara user ini dan pemilik kos
        $messages = Message::where('kost_id', $kost->id)
            ->where(function($q) use ($users_profile, $kost) {
                $q->where(function($query) use ($users_profile, $kost) {
                    $query->where('sender_id', $users_profile->id)
                          ->where('receiver_id', $kost->owner_id);
                })->orWhere(function($query) use ($users_profile, $kost) {
                    $query->where('sender_id', $kost->owner_id)
                          ->where('receiver_id', $users_profile->id);
                });
            })
            ->orderBy('created_at')
            ->get();

        return view('chat.show', compact('kost', 'messages', 'users_profile'));
    }

    /**
     * Kirim pesan baru.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'kost_id'     => 'required|exists:kosts,id',
            'sender_id'   => 'required|exists:users_profile,id',
            'receiver_id' => 'required|exists:users_profile,id',
            'message'     => 'required|string|max:1000',
        ]);

        $message = Message::create($validated);
        
        // Kirim notifikasi ke penerima pesan
        $receiver = User_profile::find($request->receiver_id);
        if ($receiver) {
            $receiver->notify(new NewMessageNotification($message));
        }

        return back()->with('success', 'Pesan terkirim!');
    }

    /**
     * Display all chats for a user.
     */
    public function history(Request $request)
    {
        $userProfileId = $request->input('user_profile_id');

        if (!$userProfileId) {
            return redirect()->route('chat.index')->withErrors('User belum dipilih.');
        }

        $users_profile = User_profile::find($userProfileId);

        if (!$users_profile) {
            return redirect()->route('chat.index')->withErrors('User tidak ditemukan.');
        }

        // Get all unique kosts where this user has had conversations
        $kosts = Kost::whereHas('messages', function($query) use ($users_profile) {
            $query->where('sender_id', $users_profile->id)
                  ->orWhere('receiver_id', $users_profile->id);
        })->get();

        // For each kost, get the latest message
        foreach ($kosts as $kost) {
            $kost->latest_message = Message::where('kost_id', $kost->id)
                ->where(function($q) use ($users_profile) {
                    $q->where('sender_id', $users_profile->id)
                      ->orWhere('receiver_id', $users_profile->id);
                })
                ->latest()
                ->first();
        }

        return view('chat.history', compact('kosts', 'users_profile'));
    }

    /**
     * Mark notifications as read
     */
    public function markAsRead(Request $request)
    {
        $userProfileId = $request->input('user_profile_id');
        $notificationId = $request->input('notification_id');
        
        $user = User_profile::find($userProfileId);
        
        if ($user && $notificationId) {
            $user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        } elseif ($user) {
            $user->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}