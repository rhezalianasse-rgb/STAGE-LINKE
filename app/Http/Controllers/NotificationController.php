<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->orderBy('date_envoi', 'desc')->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        $notification->update(['lu' => true]);

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('lu', false)->update(['lu' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function count()
    {
        if (! Auth::check()) {
            return response()->json(['count' => 0]);
        }
        $count = Auth::user()->notifications()->where('lu', false)->count();

        return response()->json(['count' => $count]);
    }
}
