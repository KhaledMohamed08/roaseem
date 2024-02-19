<?php

namespace App\Http\Controllers\API\Favorite;

use App\Http\Controllers\Controller;
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
            return redirect()->back()->with('message', 'Item removed from favorites.');
        }

        $user->favorites()->attach($unitId);
        return redirect()->back()->with('message', 'Item added to favorites.');
    }
}
