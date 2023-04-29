<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function sendNotification()
{
    // Get all new notifications from the database
    $notifications = DB::table('notification')
        ->where('status', 0) // only get unread notifications
        ->orderByDesc('dateCreate')
        ->get();

    // If there are no new notifications, return an error response
    if ($notifications->isEmpty()) {
        return response()->json([
            'error' => 'No new notifications found'
        ], 404);
    }

    // Update the status of all new notifications to "read"
    DB::table('notification')
        ->whereIn('idNotification', $notifications->pluck('idNotification')->toArray())
        ->update(['status' => 1]);

    // Format the notifications array for response
    $formattedNotifications = $notifications->map(function ($notification) {
        return [
            'id' => $notification->idNotification,
            'type' => $notification->type,
            'content' => $notification->content,
            'date_sent' => $notification->dateCreate,
        ];
    });

    // Return the array of new notifications
    return response()->json([
        'notifications' => $formattedNotifications
    ]);
}

}
