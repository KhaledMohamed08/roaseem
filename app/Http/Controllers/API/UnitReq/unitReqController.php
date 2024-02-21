<?php

namespace App\Http\Controllers\API\UnitReq;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitReqRequest;
use App\Http\Resources\unitReqResource;
use App\Http\Responses\ApiResponse;
use App\Models\UnitReq;
use App\Models\UnitReqUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class unitReqController extends Controller
{
    public function store(StoreUnitReqRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
     
        $ReqData = UnitReq::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "status" => isset($request->status) ? $request->status : null,
            "purpose" => isset($request->purpose) ? $request->purpose : null,
            "unit_types" => $request->unitType,
            "city_id" => $request->city_id,
            "area" => $request->area,
            "price" => $request->price,
            "description" => $request->description,
            "ad_period" => $request->adPeriod,
            "entity_type" => $request->entity_type,
        ]);

        if ($ReqData) {

            //save your request
            UnitReqUser::create([
                "unit_req_id"=>$ReqData->id,
                "user_id"=>$user->id,
            ]);
            
            //save the companies can see the advertisement if founded
            if($request->filled('companies'))
            {
                foreach($request->companies as $company)
                {
                    UnitReqUser::create([
                        "unit_req_id"=>$ReqData->id,
                        "user_id"=>$company
                    ]);
                }
            }

            return ApiResponse::success(
                [
                    'Unit request' => new unitReqResource($ReqData)
                ],
                'Unit Request created successfully',
                201
            );
        }

        return ApiResponse::error(
            [
                'message' => 'Something went wrong',
            ],
            301
        );
    }
}
