<?php

namespace App\Http\Controllers\API\UnitReq;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitReqRequest;
use App\Http\Requests\UpdateUnitReqRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\unitReqResource;
use App\Http\Responses\ApiResponse;
use App\Models\AdPeriod;
use App\Models\UnitReq;
use App\Models\UnitReqUser;
use App\Models\User;
use App\Services\FilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class unitReqController extends Controller
{
    protected $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(Request $request)
    {
        $sortDirection = $request->input('sort_direction', 'desc');

        $validSortDirections = ['asc', 'desc'];
        if (!in_array($sortDirection, $validSortDirections)) {
            return ApiResponse::error('Invalid sort direction.', 400);
        }
        $query = UnitReq::with('unitReqUser');

        $query->orderBy('id', $sortDirection);

        $unitReqs = $query->paginate(2);

        if ($unitReqs->isNotEmpty()) {
            return ApiResponse::success([
                'Unit request' => unitReqResource::collection($unitReqs)
            ]);
        } else {
            return ApiResponse::error('No unit requests found.', 404);
        }
    }

    public function myRequests()
    {
        $user = Auth::user();
        $myReqs = UnitReq::where('user_id', $user->id)
        ->orderby('id','desc')
        ->get();

        if ($myReqs->isNotEmpty()) {
            return ApiResponse::success([
                'Unitrequest' => unitReqResource::collection($myReqs)
            ]);
        } else {
            return ApiResponse::error('No unit requests found.', 404);
        }
    }

    public function store(StoreUnitReqRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        if ($user->role === "company") {
            return ApiResponse::error([
                'message' => 'Company cannot make request Unit',
            ], 301);
        }

        $adPeriod = AdPeriod::where('id', $request->adPeriod_id)->first();
        $daysToAdd = $adPeriod->days_num;
        
        $now = Carbon::now();
        $adPeriod = $now->addDays($daysToAdd);

        $unitReqData = $this->createUnitRequest($request, $user, $adPeriod);

        if ($unitReqData) {

            if($unitReqData->entity_type == 'companies' || 'marketers')
            {
                $this->saveUserRequest($unitReqData, $user, $request->companies);
            }

            return ApiResponse::success([
                'Unitrequest' => new unitReqResource($unitReqData)
            ], 'Unit Request created successfully', 201);
        }

        return ApiResponse::error([
            'message' => 'Something went wrong',
        ], 301);
    }

    protected function createUnitRequest($request, $user, $adPeriod)
    {
        return UnitReq::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "unit_status_id" => $request->status_id ?? null,
            "unit_purpose_id" => $request->purpose_id ?? null,
            "unit_types_id" => $request->unitType_id,
            "city_id" => $request->city_id,
            "max_area" => $request->maxArea,
            "min_area" => $request->minArea,
            "max_price" => $request->maxPrice,
            "min_price" => $request->minPrice,
            "description" => $request->description,
            'bed_rooms' => $request->bedRooms,
            'bath_rooms' => $request->bathRooms,
            "ad_period" => $adPeriod,
            "ad_period_id" => $request->adPeriod_id,
            "entity_type" => $request->entity_type,
            "user_id" => $user->id,
        ]);
    }

    protected function saveUserRequest($unitReqData, $user, $companies)
    {
        if ($companies) {
            
            if($companies == [0])
            {
                if ($unitReqData->entity_type == "marketers") {
                    $companies = User::Marketer()->get();
                } else {
                    $companies = User::companies()->get();
                }

                if($companies->isNotEmpty())
                {
                    foreach ($companies as $company) {
                        UnitReqUser::create([
                            "unit_req_id" => $unitReqData->id,
                            "user_id" => $company->id
                        ]);
                    }
                }
            }
            else
            {
                foreach ($companies as $company) {
                    UnitReqUser::create([
                        "unit_req_id" => $unitReqData->id,
                        "user_id" => $company
                    ]);
                }
            }
        }
    }

    public function edit($id)
    {
        $unitReq = UnitReq::find($id);

        if (!$unitReq) {
            return ApiResponse::error([
                'message' => 'Unit request not found',
            ], 404);
        }

        return $this->respondWithUnitReq($unitReq);
    }

    protected function respondWithUnitReq($unitReq)
    {
        $unitReq->load('unitReqUser');

        return ApiResponse::success([
            'Unitrequest' => new unitReqResource($unitReq)
        ]);
    }

    public function update(UpdateUnitReqRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $unitReq = UnitReq::with('unitReqUser')->find($request->id);

        

        if (!$unitReq) {
            return ApiResponse::error([
                'message' => 'Unit request not found',
            ], 404);
        }

        if ($user->role === "company") {
            return ApiResponse::error([
                'message' => 'Company cannot update request Unit',
            ], 301);
        }

        if($unitReq->ad_period_id != $request->adPeriod_id)
        {

            $adPeriod = AdPeriod::where('id', $request->adPeriod_id)->first();
            $daysToAdd = $adPeriod->days_num;
            
            $now = Carbon::now();
            $adPeriod = $now->addDays($daysToAdd);
        }
        else {
            $adPeriod = $unitReq->ad_period;
            // return $adPeriod ;
        }
        $adPeriodId = $request->adPeriod_id;

        $unitReqData = $this->updateUnitRequest($unitReq, $request, $user, $adPeriod, $adPeriodId);

        if($request->entity_type == 'companies' || 'marketers') {

            if ($request->companies) {
                $unitReq->unitReqUser()->delete();

                if($request->companies == [0])
                {
                    if ($request->entity_type == "marketers") {
                        $companies = User::Marketer()->get();
                    } else {
                        $companies = User::companies()->get();
                    }
    
                    if($companies->isNotEmpty())
                    {
                        foreach ($companies as $company) {
                            $unitReq->unitReqUser()->create([
                                'user_id' => $company,
                                'unit_req_id' => $request->id,
                            ]);
                        }
                    }
                } else {
                    foreach ($request->companies as $company) {
                        $unitReq->unitReqUser()->create([
                            'user_id' => $company,
                            'unit_req_id' => $request->id,
                        ]);
                    }
                }
             
            }
        }
   

        return ApiResponse::success([
            'Unitrequest' => new unitReqResource($unitReq)
        ], 'Unit Request updated successfully', 201);
    }

    protected function updateUnitRequest($unitReq, $request, $user, $adPeriod, $adPeriodId)
    {
        return $unitReq->update([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "unit_status_id" => $request->status_id ?? null,
            "unit_purpose_id" => $request->purpose_id ?? null,
            "unit_types_id" => $request->unitType_id,
            "city_id" => $request->city_id,
            "max_area" => $request->maxArea,
            "min_area" => $request->minArea,
            "max_price" => $request->maxPrice,
            "min_price" => $request->minPrice,
            "description" => $request->description,
            'bed_rooms' => $request->bedRooms,
            'bath_rooms' => $request->bathRooms,
            "ad_period" => $adPeriod,
            "ad_period_id" => $adPeriodId,
            "entity_type" => $request->entity_type,
        ]);
    }

    public function delete($id)
    {
        $unitReq = UnitReq::with('unitReqUser')->find($id);

        if (!$unitReq) {
            return ApiResponse::error([
                'message' => 'Unit request not found',
            ], 404);
        }

        if ($unitReq->unitReqUser) {
            $unitReq->unitReqUser()->delete();
        }

        $unitReq->delete();

        return ApiResponse::successWithoutData([
            'message' => 'Unit request deleted successfully',
        ]);
    }

    public function filter(Request $request)
    {
        $relationships = ['unitReqUser'];

        $sortField = "id";
        $sortDirection = $request->input('sort_direction') ?? 'desc';
        
        $filters = [
            'unit_status_id' => $request->input('status_id'),
            'unit_purpose_id' => $request->input('purpose_id'),
            'entity_type' => $request->input('entity_type'),
            'max_price' => $request->input('maxPrice'),
            'min_price' => $request->input('minPrice'),
            'max_area' => $request->input('maxArea'),
            'min_area' => $request->input('minArea'),
            // 'price' => $request->filled(['max', 'min']) ? [$request->min, $request->max] : null,
            // 'area' => $request->filled(['areaFrom', 'areaTo']) ? [$request->areaFrom, $request->areaTo] : null,
            'city_id' => $request->input('city_id'),
            'unit_types_id' => $request->input('unit_types'),
            'bed_rooms' => $request->input('bedRooms'),
            'bath_rooms' => $request->input('bathRooms')
        ];
        
        $filterService = $this->filterService->filter(UnitReq::class, $filters, $sortField, $sortDirection , $relationships);

        if ($filterService->isNotEmpty()) {
            return ApiResponse::success([
                'Unitrequest' => unitReqResource::collection($filterService)
            ], 'UnitRequest updated successfully', 201);
        }
        return ApiResponse::error('No unit requests found.', 404);
    }

    public function adPeriodIndex()
    {
        $adPeriods = AdPeriod::select('id', 'name', 'days_num')->get();

        if($adPeriods->isEmpty())
        {
            return ApiResponse::error([
                'message' => 'No periods Found',
            ], 404);
        }
        
        return ApiResponse::success($adPeriods);
    }
}
