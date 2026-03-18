<?php

namespace App\Http\Controllers\managers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreManagerStoreDepartment;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $authId = Auth::guard('web')->id();
        $storeDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();

        $storeId = $storeDepartment->store_id;

        $notifications = Notification::where('store_manager_id', $authId)
            ->where('store_id', $storeId)
            ->latest()
            ->limit(10)
            ->get();

            // return $notifications;

        $unreadCount = Notification::where('store_manager_id', $authId)
            ->where('store_id', $storeId)
            ->where('seenByUser', '0') // Count only unread notifications
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markAllAsRead()
    {
        $authId = Auth::guard('web')->id();
        $storeDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();

        $storeId = $storeDepartment->store_id;

        Notification::where('store_manager_id', $authId)
            ->where('store_id', $storeId)
            ->where('seenByUser', '0') // Mark only unread notifications as read
            ->update(['seenByUser' => '1']);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->seenByUser = '1';
            $notification->save();
            return response()->json(['message' => 'Notification marked as read']);
        }
        return response()->json(['message' => 'Notification not found'], 404);
    }
}
