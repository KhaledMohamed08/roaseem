<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function companyFilter(Request $request)
    {
        $name = $request->input('name');

        $companiesQuery = User::where('id','>','0')->companies();

        if (!empty($name)) {
            $companiesQuery->where('name', 'like', "%$name%");
        }
        $companies = $companiesQuery->limit(10)->get();

        if ($companies->isNotEmpty()) {
            return ApiResponse::success([
                'Companies' => UserResource::collection($companies)
            ]);
        } else {
            return ApiResponse::error('No companies found.', 404);
        }
    }
}
