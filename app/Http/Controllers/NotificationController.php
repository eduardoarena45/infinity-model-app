<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark all unread notifications for the authenticated user as read.
     */
    public function markAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->noContent();
    }
}
