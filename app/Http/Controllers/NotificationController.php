<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
