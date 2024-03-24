<?php

namespace App\Http\Controllers\API\AppSetting;

use App\Http\Controllers\Controller;
use App\Http\Resources\appSettingResource;
use App\Http\Resources\RegulationNewsResource;
use App\Http\Responses\ApiResponse;
use App\Models\AppSettings;
use App\Models\Complain;
use App\Models\RegulationNews;
use Illuminate\Http\Request;

class appSettingController extends Controller
{
    public function index()
    {
        $appSettings = AppSettings::get();

        if($appSettings->IsNotEmpty())
        {
            return ApiResponse::success([
                'AppSetting' => appSettingResource::collection($appSettings)
            ]);   
        }
        return ApiResponse::error('No App Setting found.', 404);
    }

    public function complaintsStore(Request $request)
    {
        $request->validate([
            "name" =>"required|string",
            "email" =>"required|string",
            "phone" =>"required|numeric",
            "message" =>"required|string"
        ]);

        Complain::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'phone' =>$request->phone,
            'message' =>$request->message
        ]);

        return ApiResponse::successWithoutData([
            'message' => 'Your complain sent successfully',
        ]);
    }

    public function regulations()
    {
        $regulations = RegulationNews::where('key', 'regulations')->get();

        if($regulations->isNotEmpty())
        {
            return ApiResponse::success([
                'regulations' => RegulationNewsResource::collection($regulations)
            ]);   
        }

        return ApiResponse::error('No regulations had been added found.', 404);
    }

    public function news()
    {
        $news = RegulationNews::where('key', 'news')->get();

        if($news->isNotEmpty())
        {
            return ApiResponse::success([
                'regulations' => RegulationNewsResource::collection($news)
            ]);   
        }

        return ApiResponse::error('No regulations had been added found.', 404);
    }
}
