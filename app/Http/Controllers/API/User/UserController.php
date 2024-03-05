<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\UnitReq;
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
}
