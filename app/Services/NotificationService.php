<?php

namespace App\Services;

use App\Models\Notification;

class NotificationsService
{
    public function createNotification($userId, $message, $event)
    {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'event' => $event,
            'is_read' => false // Assuming notifications are initially unread
        ]);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->is_read = true;
            $notification->save();
            return $notification;
        }
        return null; // Or throw an exception if not found
    }

    public function getNotificationsForUser($userId)
    {
        return Notification::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    // You can add more methods as needed for your application
}
