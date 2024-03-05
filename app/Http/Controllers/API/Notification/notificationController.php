<?php

namespace App\Http\Controllers\API\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Responses\ApiResponse;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notificationController extends Controller
{
    protected $notificationsService;

    public function __construct(NotificationService $notificationsService)
    {
        $this->notificationsService = $notificationsService;
    }

    public function index()
    {
        $notifications = $this->notificationsService->getNotificationsForUser(Auth::id());

        return ApiResponse::success(
            [
                'Notifications' => NotificationResource::collection($notifications),
            ]
        );
    }

    public function delete($notificationId)
    {
        $deleteNotification = $this->notificationsService->deleteNotification($notificationId);

        if($deleteNotification){
            return ApiResponse::successWithoutData(
                "Notifications deleted successfully"
            );        
        }
    }
}
