<?php

namespace App\Http\Controllers\API\Favorite;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Notification;
use App\Models\Unit;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $notificationsService;

    public function __construct(NotificationService $notificationsService)
    {
        $this->notificationsService = $notificationsService;
    }

    public function toggleFavorite($unitId)
    {
        $userId = auth()->id();
        $user = User::find($userId);
        $unit = Unit::where('id',$unitId)->first();
        $notification = Notification::where('user_id',$unit->user_id)->where('event',"unit_added_to_fav")->first();

        // Check if the item is already in favorites
        if ($user->favorites->contains($unitId)) {
            $user->favorites()->detach($unitId);
            if(isset($notification))
            {
                $notification->delete();
            }
            return ApiResponse::successWithoutData(
                'Item Removed To Favorites',
                200
            );
        }

        $user->favorites()->attach($unitId);
        $notification = $this->notificationsService
        ->createNotification($unit->user_id, "Your Unit added to favorites from $user->name","unit_added_to_fav","unit/$unitId");
        // return $notification;
        return ApiResponse::successWithoutData(
            'Item Added From Favorites',
            200
        );
    }

    public function getFavorites()
    {
        $user = auth()->user();

        return ApiResponse::success(
            ['favorites' => $user->favorites],
            'User Favorites Items',
            200
        );
    }
}
