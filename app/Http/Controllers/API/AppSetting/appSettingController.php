<?php

namespace App\Http\Controllers\API\AppSetting;

use App\Http\Controllers\Controller;
use App\Http\Resources\appSettingResource;
use App\Http\Resources\RealEstateNewsResource;
use App\Http\Resources\RegulationsLawsResource;
use App\Http\Responses\ApiResponse;
use App\Models\AppSettings;
use App\Models\Complain;
use App\Models\RealEstateNews;
use App\Models\RegulationsLaws;
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
        $regulations = RegulationsLaws::get();

        if($regulations->isNotEmpty())
        {
            return ApiResponse::success([
                'regulations' => RegulationsLawsResource::collection($regulations)
            ]);   
        }

        return ApiResponse::error('No regulations had been added found.', 404);
    }

    public function news()
    {
        $news = RealEstateNews::get();

        if($news->isNotEmpty())
        {
            foreach($news as $new)
            {
                foreach($new->getMedia('images') as $image)
                {
                    $image = $image->original_url;
                    $new->image = $image;
                }
            }
            return ApiResponse::success([
                'News' => RealEstateNewsResource::collection($news)
            ]);   
        }

        return ApiResponse::error('No News had been added.', 404);
    }
}
