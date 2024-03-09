<?php

namespace App\Http\Controllers;

use App\Http\Resources\UnitFeatureResource;
use App\Http\Responses\ApiResponse;
use App\Models\UnitInterface;
use App\Models\UnitPayment;
use App\Models\UnitPurpose;
use App\Models\UnitService;
use App\Models\UnitStatus;
use App\Models\UnitType;
use Illuminate\Http\Request;

class unitFeaturesController extends Controller
{
    public function getUnitStatus(UnitStatus $unitStatus)
    {
        $unitStatus = $unitStatus->get();
        if($unitStatus->isNotEmpty()) 
        {
            return ApiResponse::success(
                [
                    'unitStatus' => UnitFeatureResource::collection($unitStatus),
                ],
            );
        }
        else
        {
            return ApiResponse::error('Unit Status not found', 404);
        }
    }

    public function getUnitType(UnitType $unitType)
    {
        $unitType = $unitType->get();
        if($unitType->isNotEmpty()) 
        {
            return ApiResponse::success(
                [
                    'unitType' => UnitFeatureResource::collection($unitType),
                ],
            );
        }
        else
        {
            return ApiResponse::error('Unit Status not found', 404);
        }
    }

    public function getUnitServices(UnitService $unitService)
    {
        $unitService = $unitService->get();
        if($unitService->isNotEmpty()) 
        {
            return ApiResponse::success(
                [
                    'unitService' => UnitFeatureResource::collection($unitService),
                ],
            );
        }
        else
        {
            return ApiResponse::error('Unit Status not found', 404);
        }
    }

    public function getunitPurpose(UnitPurpose $unitPurpose)
    {
        $unitPurpose = $unitPurpose->get();
        if($unitPurpose->isNotEmpty()) 
        {
            return ApiResponse::success(
                [
                    'unitPurpose' => UnitFeatureResource::collection($unitPurpose),
                ],
            );
        }
        else
        {
            return ApiResponse::error('Unit Status not found', 404);
        }
    }

    public function getunitPayment(UnitPayment $unitPayment)
    {
        $unitPayment = $unitPayment->get();
        if($unitPayment->isNotEmpty()) 
        {
            return ApiResponse::success(
                [
                    'unitPayment' => UnitFeatureResource::collection($unitPayment),
                ],
            );
        }
        else
        {
            return ApiResponse::error('Unit Status not found', 404);
        }
    }

    public function getunitInterFace(UnitInterface $unitInterFace)
    {
        $unitInterFace = $unitInterFace->get();
        if($unitInterFace->isNotEmpty()) 
        {
            return ApiResponse::success(
                [
                    'unitInterFace' => UnitFeatureResource::collection($unitInterFace),
                ],
            );
        }
        else
        {
            return ApiResponse::error('Unit Status not found', 404);
        }
    }
}
