<?php

// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    public function destroy($id)
    {
        $notification = DatabaseNotification::where('id', $id)
                    ->where('notifiable_id', auth()->id())
                    ->firstOrFail();

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
