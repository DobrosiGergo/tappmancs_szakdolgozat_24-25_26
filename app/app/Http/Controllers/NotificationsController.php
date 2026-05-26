<?php

namespace App\Http\Controllers;

class NotificationsController extends Controller
{
    public function index()
    {
        $user          = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        $user->unreadNotifications()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }
}
