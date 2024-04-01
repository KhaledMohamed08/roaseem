<?php

namespace App\Http\Controllers\API\Auction;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Http\Resources\AuctionResource;
use App\Http\Responses\ApiResponse;
use App\Models\Auction;
use App\Services\FilterService;
use App\Models\Property;
use Illuminate\Http\Request;
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
        $newAuction = Auction::create($request->validated());
        $newAuction->addMediaFromRequest('auction_pdf_file')->toMediaCollection('auction_pdf_file');
        $newAuction->addMediaFromRequest('main_auction_image')->toMediaCollection('main_auction_image');

        foreach ($request['properties'] as $property) {
            $property['auction_id'] = $newAuction->id;
            $newProperty = Property::create($property);
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
        $auction->update($request->validated());

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
}
