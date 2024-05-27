<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialMediaResource;
use App\Http\Responses\ApiResponse;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class socialMediaController extends Controller
{
    public function index()
    {
        $socialMedia = SocialMedia::all();

        if($socialMedia)
        {
            foreach($socialMedia as $social)
            {
                foreach($social->getMedia('images') as $icon) {
                    // $icon = $icon->getMedia('images');
                    // $social->icon = $icon;
                    $dashUrl = "https://dash.roaseem.magdsofteg.xyz/storage";
                    $icon = "$dashUrl/$icon->id/$icon->file_name";
                    $social->icon = $icon;
                }
            }
            return ApiResponse::success(SocialMediaResource::collection($socialMedia));   
        }
        return ApiResponse::error('No App Setting found.', 404);
    }
}
