<?php

namespace App\Http\Controllers\API\AppSetting;

use App\Http\Controllers\Controller;
use App\Http\Resources\appSettingResource;
use App\Http\Responses\ApiResponse;
use App\Models\AppSettings;
use Illuminate\Http\Request;

class appSettingController extends Controller
{
    public function index()
    {
        $appSettings = AppSettings::get();

        if($appSettings->IsNotEmpty())
        {
            return ApiResponse::success([
                'App Setting' => appSettingResource::collection($appSettings)
            ]);   
        }
        return ApiResponse::error('No App Setting found.', 404);
    }
}
