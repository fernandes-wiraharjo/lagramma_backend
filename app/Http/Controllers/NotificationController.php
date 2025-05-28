<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = auth()->user();

        // Check user role
        $roleName = $user->role->name ?? null;  // Adjust if role is stored differently

        if ($roleName !== 'customer') {
            $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();
        } else {
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        // Calculate unread count
        if ($roleName !== 'customer') {
            $unreadCount = Notification::where('is_read', false)->count();
        } else {
            $unreadCount = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
        }

        // Format notification data
        $data = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->message ?? 'Notification',
                // 'message' => $notification->title ?? 'Message',
                'url' => $notification->link ?? '#',
                'read' => $notification->is_read,
                'time_ago' => $notification->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $data,
        ]);
    }

    public function markRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->is_read = true;
            $notification->updated_by = auth()->id();
            $notification->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
