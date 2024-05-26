<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MazadProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return ApiResponse::success(
            [
                'user' => new UserResource($user),
            ],
            'User Profile',
            200
        );
    }

    public function edit(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        // $validatedData = $request->validated();
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => '',
            'nid' => 'required',
            'nidImage' => 'required',
        ]);

        if ($user) {
            // $user->update($validatedData);
            $user->update([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'nid' => $validatedData['nid'],
            ]);

            if ($request->hasFile('image')) {
                $user->clearMediaCollection('logo');
                $user->addMediaFromRequest('image')->toMediaCollection('logo');
            }

            if ($request->hasFile('nidImage')) {
                $user->addMediaFromRequest('nidImage')->toMediaCollection('nidImage');
            }
            

            return ApiResponse::success(
                [
                    'user' => new UserResource($user),
                ],
                'Profile Updated Successfully',
                200
            );
        } else {
            return ApiResponse::error(
                'User Not Found',
                404
            );        
        }
    }

    
}
