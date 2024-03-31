<?php

namespace App\Http\Controllers\API\Auction;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Http\Resources\AuctionResource;
use App\Http\Responses\ApiResponse;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = 9;
        $auctions = Auction::orderBy('created_at', 'desc')->paginate($perPage);

        return ApiResponse::success(
            [
                'auctions' => AuctionResource::collection($auctions),
            ],
            'Auctions Retrieved Successfully',
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuctionRequest $request)
    {
        $newAuction = Auction::create($request->validated());

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
