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
use Illuminate\Validation\Rule;

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

    public function edit(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);
        // $validatedData = $request->validated();
        $validatedData = $request->validate([
            'name' => 'required',
            // 'phone' => [
            //     'required',
            //     Rule::unique('users')->ignore($userId, 'id'),
            // ],
            'email' => [
                // 'required',
                // Rule::unique('users')->where(function ($query) use ($userId) {
                //     return $query->where('id', '!=', $userId);
                // }),
                Rule::unique('users')->ignore($user),
            ],
            'about' => '',
            'whatsapp' => '',
            'land_line' => '',
            'longitude' => '',
            'latitude' => '',
            'address' => '',
        ]);

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
        // $numOfFavorites = $this->numOfFavorites($user->id);

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
                // 'numOfFavorites' => $numOfFavorites,
            ],
            'Unit Statistics',
            200
        );
    }

    public function userUnitesStatisticsById(User $user) {
        // $user = Auth::user();
        $numOfEachUnitType = $this->numOfEachUnitType($user->id);
        $numOfEachUnitStatus = $this->numOfEachUnitStatus($user->id);
        $numOfEachUnitPurpos = $this->numOfEachUnitPurpos($user->id);
        $numOfEachUnitCities = $this->numOfEachUnitCities($user->id);
        // $numOfFavorites = $this->numOfFavorites($user->id);

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
                // 'numOfFavorites' => $numOfFavorites,
            ],
            'Unit Statistics',
            200
        );
    }

    public function userUnitesStatisticsForMobile() {
        $user = Auth::user();
        $numOfEachUnitType = $this->numOfEachUnitType($user->id);
        // dd($numOfEachUnitType);
        $numOfEachUnitStatus = $this->numOfEachUnitStatus($user->id);
        $numOfEachUnitPurpos = $this->numOfEachUnitPurpos($user->id);
        $numOfEachUnitCities = $this->numOfEachUnitCities($user->id);
        $numOfFavorites = $this->numOfFavorites($user->id);
        

        return response(
            [
                'numOfEachUnites' => [
                    'total' => $user->unites->count(),
                    'details' => collect(array_map(function ($key, $value) {
                        return ['title' => $key, 'value' => $value];
                    }, array_keys($numOfEachUnitType), array_values($numOfEachUnitType))),
                    
                ],
                   
                'numOfEachUnitStatus' => [
                    'total' => count($numOfEachUnitStatus),
                    'details' => collect(array_map(function ($key, $value) {
                        return ['title' => $key, 'value' => $value];
                    }, array_keys($numOfEachUnitStatus), array_values($numOfEachUnitStatus))),
                ],
                'numOfEachUnitPurpos' => [
                    'total' => count($numOfEachUnitPurpos),
                    'details' => collect(array_map(function ($key, $value) {
                        return ['title' => $key, 'value' => $value];
                    }, array_keys($numOfEachUnitPurpos), array_values($numOfEachUnitPurpos))),
                ],
                'numOfEachUnitCities' => [
                    'total' => count($numOfEachUnitCities),
                    'details' => collect(array_map(function ($key, $value) {
                        return ['title' => $key, 'value' => $value];
                    }, array_keys($numOfEachUnitCities), array_values($numOfEachUnitCities))),
                ],
                'numOfFavorites' => $numOfFavorites,
            ]
            );
    }

    protected function numOfEachUnitType($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        
        $numOfEachUnitType = [];

        foreach ($unites as $unit) {
            $numOfEachUnitType[$unit->type->name] = ($numOfEachUnitType[$unit->type->name] ?? 0) + 1;
        }

        return $numOfEachUnitType;
    }

    protected function numOfEachUnitStatus($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        
        $numOfEachUnitStatus = [];

        // foreach ($unites as $unit) {
        //     switch ($unit->contract_type) {
        //         case 'sale':
        //             $numOfEachUnitStatus['sale'] = ($numOfEachUnitStatus['sale'] ?? 0) + 1;
        //             break;
        //         case 'rent':
        //             $numOfEachUnitStatus['rent'] = ($numOfEachUnitStatus['rent'] ?? 0) + 1;
        //             break;
        //     }
        // }
        foreach ($unites as $unit) {
            $numOfEachUnitStatus[$unit->status->name] = ($numOfEachUnitStatus[$unit->status->name] ?? 0) + 1;
        }

        return $numOfEachUnitStatus;
    }

    protected function numOfEachUnitPurpos($id)
    {
        $user = User::find($id);
        $unites = $user->unites;
        
        $numOfEachUnitPurpos = [];

        // foreach ($unites as $unit) {
        //     switch ($unit->purpos) {
        //         case 'residential':
        //             $numOfEachUnitPurpos['residential'] = ($numOfEachUnitPurpos['residential'] ?? 0) + 1;
        //             break;
        //         case 'commercial':
        //             $numOfEachUnitPurpos['commercial'] = ($numOfEachUnitPurpos['commercial'] ?? 0) + 1;
        //             break;
        //     }
        // }
        foreach ($unites as $unit) {
            $numOfEachUnitPurpos[$unit->purpose->name] = ($numOfEachUnitPurpos[$unit->purpose->name] ?? 0) + 1;
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
        dd($user);
        $numOfFavorites = $user->unites->filter(function ($unit) {
            return $unit->favoritedBy !== null;
        })->sum(function ($unit) {
            return $unit->favoritedBy->count();
        });
        dd($numOfFavorites);

        return $numOfFavorites;
    }


    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required',
            'newPassword' => 'required|confirmed|min:6'
        ]);
        $userId = Auth::id();
        $user = User::find($userId);
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
            'Wrong Old Password',
        );
    }

    public function companyMarketerssNumbers()
    {
        $company = Auth::user();
        if ($company->role != 'company') {
            return ApiResponse::error(
                'Only Companies Can Register Employees',
                400
            );
        }

        $activeUsers = User::where('company_id', $company->id)->where('is_active', 1)->count();
        $non_activeUsers = User::where('company_id', $company->id)->where('is_active', 0)->count();
        // $allUsers = $activeUsers + $non_activeUsers;

        return ApiResponse::success(
            [
                'all_users' => $activeUsers + $non_activeUsers,
                'active_users' => $activeUsers,
                'not_active_users' => $non_activeUsers,
            ],
            ' Company Users Data',
            200
        );
    }

    public function addMarketerForCompany(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required|confirmed|min:6',
            'permissions' => 'array',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['role'] = 'marketer';
        $validatedData['company_id'] = Auth::id();
        unset($validatedData['permissions']);
        $marketer = User::create($validatedData);
        if ($request->has('permissions') && is_array($request->permissions)) {
            $marketer->syncPermissions($request->permissions);
        }
        return ApiResponse::success(
            [
                'marketer' => new UserResource($marketer),
                // 'permissions' => $marketer->getAllPermissions(),
            ],
            'Marketer Created Successfully',
            200
        );
    }

    public function companyMarketers()
    {
        $company = Auth::user();
        if ($company->role != 'company') {
            return ApiResponse::error(
                'Only Companies Has Marketers',
                400
            );
        }
        $marketers = User::where('company_id', $company->id)->get();
        
        return ApiResponse::success(
            [
                'marketers' => UserResource::collection($marketers),
            ],
            'Company Marketers',
            200
        );
    }

    public function marketerActiveToggle(User $user)
    {
        $company = Auth::user();
        if ($user->company_id != $company->id) {
            return ApiResponse::error(
                'Cannot make changes for this Marketer from this Company',
                400
            );
        }
        // $user->update([
        //     'is_active' => !$user->is_active
        // ]);
        $user->is_active = !$user->is_active;
        $user->save();

        return ApiResponse::success(
            ['User Active' => $user->is_active],
            'User Updated Successfully',
            200
        );
    }

    public function companyMarketersSearch(Request $request)
    {
        $company = Auth::user();
        if ($company->role != 'company') {

        }

        $marketers = User::where('company_id', $company->id)
                  ->where('name', 'like', '%' . $request->search . '%')
                  ->get();

        return ApiResponse::success(
            [
                'marketers' => UserResource::collection($marketers),
            ],
            'Company Users',
            200,
        );
    }

    public function updateMarketer(Request $request, User $user)
    {
        if ($user->role != 'marketer' || $user->company_id != Auth::id()) {
            return ApiResponse::error(
                'Cant Update This User',
                400,
            );
        }
        $validatedData = $request->validate([
            'name' => 'required',
            // 'email' => 'required|email|unique:users,email',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'permissions' => 'array',
        ]);

        $user->update($validatedData);

        if ($request->has('permissions') && is_array($request->permissions)) {
            $user->syncPermissions($request->permissions);
        }

        return ApiResponse::success(
            [
                'marketer' => new UserResource($user),
                // 'permissions' => $user->getAllPermissions(),
            ],
            'Marketer Updated Successfully',
            200
        );
    }

    public function deleteMarketer(User $user)
    {
        if ($user->role != 'marketer' || $user->company_id != Auth::id()) {
            return ApiResponse::error(
                'Cant Delete This User',
                400,
            );
        }
        $user->delete();

        return ApiResponse::success(
            [
                'marketer' => new UserResource($user),
            ],
            'Marketer Deleted Successfully',
            200
        );
    }
    
    public function showMarketer(User $user)
    {
        return ApiResponse::success(
            [
                'marketer' => new UserResource($user),
                // 'permissions' => $user->getAllPermissions(),
            ],
            'Marketer Data',
            200
        );
    }

    public function deleteAccount()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $user->delete();

        return ApiResponse::success(
            [
                'user' => new UserResource($user)
            ],
            'User Deleted Successfuly',
            200
        );
    }
}
