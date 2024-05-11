<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function companyFilter()
    {
        $companies = User::companies()->get();

        if($companies->isNotEmpty()) {

            return ApiResponse::success([
                'Companies' => UserResource::collection($companies)
            ]);
            
        } else {
            return ApiResponse::error('No Companies found.', 404);
        }
    }

    public function allMarketer()
    {
        $marketers = User::marketer()->get();

        if ($marketers->isNotEmpty()) {
            return ApiResponse::success([
                'marketers' => UserResource::collection($marketers)
            ]);
        } else {
            return ApiResponse::error('No marketers found.', 404);
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        if (empty($role)) {
            $role = '%'; 
        }

        $users = User::where('name', 'like', "%$search%")
            ->where('role', 'like', $role)
            ->get();

        if ($users->isNotEmpty()) {
            return ApiResponse::success([
                'Users' => UserResource::collection($users)
            ]);
        } else {
            return ApiResponse::error('No Users found.', 404);
        }
    }

    public function getUser($id)
    {
        $user = User::where('id', $id)->first();
        $units = Unit::where('user_id', $user->id)->get();

        if ($user) {
            return ApiResponse::success([
                'user' => new UserResource($user),
                'units' => UnitResource::collection($units)
            ]);
        }
        return ApiResponse::error(['User Not Found'], 404);
    }

    public function companiesMarketers()
    {
        $companies = User::companies()->select('id', 'name')->get();
        $marketers = User::marketer()->select('id', 'name')->get();

        return ApiResponse::success([
            'companies' => $companies,
            'marketers' => $marketers
        ]);
    }
}
