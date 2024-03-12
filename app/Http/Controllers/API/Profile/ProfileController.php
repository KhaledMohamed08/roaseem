<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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

    public function edit(UpdateUserRequest $request)
    {
        $validatedData = $request->validated();
        $userId = Auth::id();
        $user = User::find($userId);

        if ($user) {
            $user->update($validatedData);

            if ($request->hasFile('image')) {
                $user->clearMediaCollection('logo');
                $user->addMediaFromRequest('image')->toMediaCollection('logo');
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

    public function myUnites()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $unites = $user->unites;
        return ApiResponse::success(
            [
                'data' => UnitResource::collection($unites),
            ],
            'User Unites',
            200,
        );
    }

    public function userUnitesStatistics() {
        $user = Auth::user();
        $numOfEachUnitType = $this->numOfEachUnitType($user->id);
        $numOfEachUnitStatus = $this->numOfEachUnitStatus($user->id);
        $numOfEachUnitPurpos = $this->numOfEachUnitPurpos($user->id);
        $numOfEachUnitCities = $this->numOfEachUnitCities($user->id);
        $numOfFavorites = $this->numOfFavorites($user->id);

        return ApiResponse::success(
            [
                'numOfEachUnites' => [
                    'total' => $user->unites->count(),
                    'details' => $numOfEachUnitType,
                ],
                'numOfEachUnitStatus' => [
                    'total' => count($numOfEachUnitStatus),
                    'details' => $numOfEachUnitStatus,
                ],
                'numOfEachUnitPurpos' => [
                    'total' => count($numOfEachUnitPurpos),
                    'details' => $numOfEachUnitPurpos,
                ],
                'numOfEachUnitCities' => [
                    'total' => count($numOfEachUnitCities),
                    'details' => $numOfEachUnitCities,
                ],
                'numOfFavorites' => $numOfFavorites,
            ],
            'Unit Statistics',
            200
        );
    }

    protected function numOfEachUnitType($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        
        $numOfEachUnitType = [];

        foreach ($unites as $unit) {
            switch ($unit->unit_type) {
                case 'apartment':
                    $numOfEachUnitType['apartment'] = ($numOfEachUnitType['apartment'] ?? 0) + 1;
                    break;
                case 'land':
                    $numOfEachUnitType['land'] = ($numOfEachUnitType['land'] ?? 0) + 1;
                    break;
                case 'exhibition':
                    $numOfEachUnitType['exhibition'] = ($numOfEachUnitType['exhibition'] ?? 0) + 1;
                    break;
            }
        }

        return $numOfEachUnitType;
    }

    protected function numOfEachUnitStatus($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        
        $numOfEachUnitStatus = [];

        foreach ($unites as $unit) {
            switch ($unit->contract_type) {
                case 'sale':
                    $numOfEachUnitStatus['sale'] = ($numOfEachUnitStatus['sale'] ?? 0) + 1;
                    break;
                case 'rent':
                    $numOfEachUnitStatus['rent'] = ($numOfEachUnitStatus['rent'] ?? 0) + 1;
                    break;
            }
        }

        return $numOfEachUnitStatus;
    }

    protected function numOfEachUnitPurpos($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        
        $numOfEachUnitPurpos = [];

        foreach ($unites as $unit) {
            switch ($unit->purpos) {
                case 'residential':
                    $numOfEachUnitPurpos['residential'] = ($numOfEachUnitPurpos['residential'] ?? 0) + 1;
                    break;
                case 'commercial':
                    $numOfEachUnitPurpos['commercial'] = ($numOfEachUnitPurpos['commercial'] ?? 0) + 1;
                    break;
            }
        }

        return $numOfEachUnitPurpos;
    }

    protected function numOfEachUnitCities($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        
        $numOfEachUnitCities = [];

        foreach ($unites as $unit) {
            $region = Region::find($unit->region_id);
            $city = $region->city;
            $numOfEachUnitCities[$city->name] = ($numOfEachUnitCities[$city->name] ?? 0) + 1;
        }

        return $numOfEachUnitCities;
    }

    protected function numOfFavorites($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        $numOfFavorites = 0;
        foreach ($unites as $unit) {
            if ($unit->favoritedBy != null) {
                $numOfFavorites += $unit->favoritedBy->count(); 
            }
        }

        return $numOfFavorites;
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required',
            'newPassword' => 'required|confirmed|min:6'
        ]);
        $user = Auth::user();
        if (Hash::check($validatedData['password'], $user->password)) {
            $validatedData['newPassword'] = Hash::make($validatedData['newPassword']);
            $user->update([
                'password' => $validatedData['newPassword'],
            ]);

            return ApiResponse::successWithoutData(
                'User Password Updated Successfully',
                200
            );
        }

        return ApiResponse::error(
            'Wrong Old Password'
        );
    }
}
