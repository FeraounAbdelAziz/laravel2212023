<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function sendNotification()
{
    $notifications = DB::table('notification')
        ->where('status', 0)
        ->orderByDesc('dateCreate')
        ->get();

    if ($notifications->isEmpty()) {
        return response()->json([
            'error' => 'No new notifications found'
        ], 404);
    }
    DB::table('notification')
        ->whereIn('idNotification', $notifications->pluck('idNotification')->toArray())
        ->update(['status' => 1]);

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
