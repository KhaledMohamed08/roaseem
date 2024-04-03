<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function companyFilter()
    {
        $companies = User::companies()->get();

        if($companies->isNotEmpty())
        {
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

        if($marketers->isNotEmpty())
        {
            return ApiResponse::success([
                'marketers' => UserResource::collection($marketers)
            ]);
        } else {
            return ApiResponse::error('No marketers found.', 404);
        }
    }

    public function search(Request $request)
    {
 
        $users = User::where('name', 'like' ,"%$request->search%")
        ->where('role', 'like' ,$request->role)
        ->get();

        if($users->isNotEmpty())
        {
            return ApiResponse::success([
                'Users' => UserResource::collection($users)
            ]);
        } else {
            return ApiResponse::error('No Users found.', 404);
        }
    }
}
