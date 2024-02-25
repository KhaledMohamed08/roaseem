<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(User $user)
    {
        return $user->unites;
        return ApiResponse::success(
            [
                'user' => new UserResource($user),
            ],
            'User Profile',
            200
        );
    }

    public function edit(UpdateUserRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        if ($user) {
            $user->update($validatedData);
            return $user;
        } else {
            // Handle the case where the user is not found
            return ApiResponse::success(
                [
                    'user' => new UserResource($user),
                ],
                'Profile Updated Successfully',
                200
            );
        }
    }

    public function myUnites()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $myUnites = $user->unites;
        return response()->json($myUnites);
    }
}
