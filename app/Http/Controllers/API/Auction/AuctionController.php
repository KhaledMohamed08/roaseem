<?php

namespace App\Http\Controllers\API\Auction;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Http\Resources\AuctionResource;
use App\Http\Resources\PropertyResource;
use App\Http\Responses\ApiResponse;
use App\Models\Auction;
use App\Models\AuctionDetails;
use App\Models\AuctionUser;
use App\Services\FilterService;
use App\Models\Property;
use App\Models\Service;
use App\Models\User;
use App\Services\SendSms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class AuctionController extends Controller
{
    protected $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;  
    }

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $perPage = 9;
    //     $auctions = Auction::orderBy('created_at', 'desc')->paginate($perPage);

    //     return ApiResponse::success(
    //         [
    //             'auctions' => AuctionResource::collection($auctions),
    //         ],
    //         'Auctions Retrieved Successfully',
    //         200
    //     );
    // }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'city_id' => $request->input('city_id'),
            'region_id' => $request->input('region_id'),
            'status' => $request->input('status'),
        ];
        
        $filterService = $this->filterService->filter(Auction::class, $filters);

        if ($filterService->isNotEmpty()) {
            return ApiResponse::success([
                'Auction' => AuctionResource::collection($filterService)
            ]);
        }
        return ApiResponse::error('No Aucthions found.', 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuctionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $newAuction = Auction::create($data);
        $newAuction->addMediaFromRequest('auction_pdf_file')->toMediaCollection('auction_pdf_file');
        $newAuction->addMediaFromRequest('main_auction_image')->toMediaCollection('main_auction_image');

        foreach ($request['properties'] as $property) {
            $property['auction_id'] = $newAuction->id;
            $newProperty = Property::create($property);
            
            foreach ($property['images'] as $image) {
                $newProperty->addMedia($image)->toMediaCollection('property-images');
            }
        }

        return ApiResponse::success(
            [
                'Auction ' => new AuctionResource($newAuction),
            ],
            'Auction Created Successfully',
            200,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        $properties = Property::where('auction_id', $auction->id)->paginate(1);
        $auction->properties = $properties;
 
        return ApiResponse::success(
            [
                'Auction' => new AuctionResource($auction),
            ],
            'Auction',
            200,
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuctionRequest $request, Auction $auction)
    {
        $data = $request->validated();
        $auction->update($data);
        

        return ApiResponse::success(
            [
                'Auction' => new AuctionResource($auction->fresh()),
            ],
            'Auction Updated Successfully',
            200,
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction)
    {
        $auction->delete();

        return ApiResponse::success(
            [
                'Auction' => new AuctionResource($auction),
            ],
            'Auction Deleted Successfully',
            200
        );
    }

    public function showMyOrders()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        $orders = $user->auctions;
        return ApiResponse::success(
            [
                'number of auctions' => $orders->count(),
                'auctions' => AuctionResource::collection($orders),
            ],
            'Auctions Created By' . ' ' . $user->name,
            200,
        );
    }

    // public function pushAmountInAuction(Request $request, Auction $auction)
    // {
    //     $user = Auth::user();
    //     $userAuctions = $user->auctions;
        
    //     if (!$userAuctions->contains($auction)) {
    //         return ApiResponse::error([
    //             'You did not subscibed in this Auction!'
    //         ]);
    //     }

    //     $timeToPush = Carbon::now();
    //     $auctionEndTime = $auction->end_date;

    //     // if ($timeToPush->isAfter($auctionEndTime)) {
    //     //     return ApiResponse::error(
    //     //         'the auction has ended!',
    //     //         400
    //     //     );
    //     // }

    //     if ($timeToPush->isAfter($auctionEndTime)) {
    //         return ApiResponse::error(
    //             'the auction has ended!',
    //             400
    //         );
    //     }

    //     if ($auction->details) {
    //         if (intval($request['mount']) >= (intval($auction->details->max_price) + intval($auction->minimum_bid))) {
    //             $auction->details->max_price = $request['mount'];
    //             $auction->details->max_user = Auth::id();
    //             $auction->details->save();

    //             return ApiResponse::success(
    //                 [
    //                     'auction' => new AuctionResource($auction),
    //                 ],
    //                 'Mount Pushed Successfully',
    //                 200,
    //             );
    //         } else {
    //             return ApiResponse::error(
    //                 'Failed push. Min price you can push is ' . (intval($auction->details->max_price) + intval($auction->minimum_bid)),
    //                 400
    //             );
    //         }
    //     }
    // }

    public function pushAmountInAuction(Request $request)
    {
        $user = Auth::user();

        $maxPrice = AuctionDetails::max('max_price');
        $pastpush = AuctionDetails::where('auction_id', $request->auctionId)->where('max_user', $user->id)->first();

        $subscription = AuctionUser::where('user_id', $user->id)
        ->where('auction_id', $request->auctionId)
        ->first();

        if(!$subscription)
        {
            return ApiResponse::error([
                'You did not subscibed in this Auction!'
            ]);
        }

        $timeToPush = Carbon::now();
        $auction = $subscription->auction;
        $auctionEndTime = $auction->end_date;

        if ($timeToPush->isAfter($auctionEndTime)) {
            return ApiResponse::error(
                'the auction has ended!',
                400
            );
        }

        if($pastpush)
        {
            if ($request->mount < $auction->minimum_bid || $request->mount <= $maxPrice) {

                return ApiResponse::error(
                    "Failed push. Min price you can push is " . $maxPrice + $auction->minimum_bid,
                    400
                );
            }
        }
        elseif ($maxPrice > $request->mount && $request->mount <= $auction->opening_price + $auction->minimum_bid)
        {

            return ApiResponse::error(
                "Failed push. Min price you can push is" . $maxPrice + $auction->minimum_bid,
                400
            );
        } 
        // else {
        AuctionDetails::updateOrCreate(
            ['auction_id' => $request->auctionId , 'max_user' => $user->id],
            [
                'auction_id' => $request->auctionId,
                'max_price'=> $request->mount,
                'max_user'=> $user->id,
            ]
        );

        return ApiResponse::successWithoutData(
            'Mount Pushed Successfully',
            200,
        );
        // }

    }

    public function winnerPay()
    {
        $winningPrice = AuctionDetails::get()->max('max_price');

        $tax = $winningPrice * 0.05 ;
        $tax2 = $winningPrice * 0.025;
        $tax3 = $tax * 0.15; 

        $finalPrice = $winningPrice + $tax + $tax2 + $tax3;

        dd($winningPrice,$finalPrice);
    }

    public function auctionDetails(Auction $auction)
    {
        return $auction->user;
    }

    // public function showPropertyAuction($id)
    // {
    //     $properties = Property::where('auction_id', $id)->paginate(1);

    //     if($properties)
    //     {
    //         dd($properties);
    //         return ApiResponse::success(
    //             [
    //                 'properties' => PropertyResource::collection($properties),
    //                 'pagination' => [

    //                 ]
    //             ],
    //             'properties',
    //             200,
    //         );
    //     }
    // }
}
