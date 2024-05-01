<?php

namespace App\Http\Controllers\API\VerificationServices;

use App\Http\Controllers\Controller;
use App\Http\Resources\VerificationServiceResource;
use App\Http\Responses\ApiResponse;
use App\Models\VerificationService;
use Illuminate\Http\Request;

class VerificationServiceController extends Controller
{
    public function index()
    {
        $verificationServices = VerificationService::all();
        
        if($verificationServices->isNotEmpty())
        {
            foreach($verificationServices as $verificationService)
            {
                foreach($verificationService->getMedia('images') as $image)
                {
                    // $image = $image->original_url;
                    $dashUrl = "https://dash.roaseem.magdsofteg.xyz/storage";
                    $image = "$dashUrl/$image->id/$image->file_name";
                    $verificationService->image = $image;
                }
            }
            
            return ApiResponse::success([
                'VerificationServices' => VerificationServiceResource::collection($verificationServices)
            ]);
        }
        return ApiResponse::error('No VerificationServices found.', 404);
    }

    public function search(Request $request)
    {
        $verificationServices = VerificationService::where('name', 'like' , "%$request->name%")->get();

        if($verificationServices->isNotEmpty())
        {
            foreach($verificationServices as $verificationService)
            {
                foreach($verificationService->getMedia('images') as $image)
                {
                    $image = $image->original_url;
                    $verificationService->image = $image;
                }
            }
            
            return ApiResponse::success([
                'VerificationServices' => VerificationServiceResource::collection($verificationServices)
            ]);
        }

    }
}
