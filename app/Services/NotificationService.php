<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function createNotification($userId, $message, $event, $url = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'event' => $event,
            'is_read' => false,
            'url' => $url
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
        return Notification::where('user_id', $userId)->orderBy('id', 'desc')->get();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->delete();
            return true;
        }
        return false; 
    }


}
