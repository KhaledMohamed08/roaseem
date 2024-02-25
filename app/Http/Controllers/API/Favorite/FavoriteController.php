<?php

namespace App\Http\Controllers\API\Favorite;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggleFavorite($unitId)
    {
        $userId = auth()->id();
        $user = User::find($userId);

        // Check if the item is already in favorites
        if ($user->favorites->contains($unitId)) {
            $user->favorites()->detach($unitId);
            return ApiResponse::successWithoutData(
                'Item Added To Favorites',
                200
            );
        }

        $user->favorites()->attach($unitId);
        return ApiResponse::successWithoutData(
            'Item Removed From Favorites',
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
