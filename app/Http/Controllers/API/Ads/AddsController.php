<?php

namespace App\Http\Controllers\API\Ads;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdsResource;
use App\Http\Responses\ApiResponse;
use App\Models\Add;
use Illuminate\Http\Request;

class AddsController extends Controller
{
    public function index()
    {
        $ads = Add::all();

        if ($ads)
        {
            foreach($ads as $ad)
            {
                foreach($ad->getMedia('images') as $image)
                {
                    // $image = $image->original_url;
                    $dashUrl = "https://dash.roaseem.magdsofteg.xyz/storage";
                    $image = "$dashUrl/$image->id/$image->file_name";
                    $ad->image = $image;
                }
            }
            // return $ads;
            return ApiResponse::success([
                'Ads' => AdsResource::collection($ads)
            ]);   
        }
    }
}
