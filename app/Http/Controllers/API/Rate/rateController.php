<?php

namespace App\Http\Controllers\API\Rate;

use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Http\Responses\ApiResponse;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class rateController extends Controller
{
    public function index()
    {
        $rates = Rate::all();

        if($rates)
        {
            return ApiResponse::success([
                'rate' => RateResource::collection($rates),
            ]);
        }
        return ApiResponse::error([
            'message' => 'there is no rates added',
        ], 404);
    }

    public function getOne($ratedId)
    {
        $rate = Rate::where('rated_user_id', $ratedId)->get();

        $totalRates = $rate->sum('rate');
        $countRates = $rate->count();
        
        if ($countRates > 0) {
            $averageRate = $totalRates / $countRates;
        } else {
            $averageRate = 0;
        }

        if($rate->isEmpty())
        {
            return ApiResponse::error([
                'message' => 'Rate Not found',
            ], 404);
        }

        return ApiResponse::success([
            'averageRate' => $averageRate,
            'rate' =>RateResource::collection($rate),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate'=>"required|numeric",
            'comment'=>"string",
            'rated_id'=>"required",
        ]);
        $rater = Auth::user();

        $rate = Rate::updateOrCreate(
            ['id' => $request->id],
            [
                "rate" => $request->rate,
                "comment" => $request->comment,
                "rated_user_id" => $request->rated_id,
                "rater_user_id" => $rater->id,
            ]);

        return ApiResponse::successWithoutData([
            'message' => 'Your rate has been saved successfully',
        ], 201);
    }

    public function delete($id)
    {
        $rate = Rate::find($id);

        if($rate)
        {
            $rate->delete();
            return ApiResponse::successWithoutData([
                'message' => 'rate Deleted successfully',
            ], 200);
        }
        return ApiResponse::error([
            'message' => 'this rate is not Found',
        ], 404);
    }


}
