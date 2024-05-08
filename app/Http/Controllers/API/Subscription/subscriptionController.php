<?php

namespace App\Http\Controllers\API\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuctionResource;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\Auction;
use App\Models\AuctionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class subscriptionController extends Controller
{
    public function auctionSubscripe($id)
    {
        $subscription_fee = Auction::where('id', $id)->pluck('subscription_fee');

        if($subscription_fee)
        {
            return ApiResponse::success([
                'data' => $subscription_fee
            ]);
        } else {
            return ApiResponse::error('No auctions found.', 404);
        }

    }

    public function subscripeStore(Request $request)
    {
        $auction = Auction::find($request->auction_id);
        $userId = Auth::id();
        
        if($auction)
        {
            $auctionUser = AuctionUser::where('user_id', $userId)->where('auction_id', $request->auction_id)->first();
            if($auctionUser) 
            {
                return ApiResponse::error("You can't Subscripe to the auction more than one time", 403);
            }
            AuctionUser::create([
                'user_id' => $userId,
                'auction_id' => $request->auction_id,
            ]);

            return ApiResponse::successWithoutData([
                'message' => "You have subscribed successfully"
            ]);

        } else {
            return ApiResponse::error('Auction not found.', 404);
        }
    }

    public function auctionSubscripers($id)
    {
        $auctionSubscripers = AuctionUser::where('auction_id', $id)->get();

        if($auctionSubscripers->isEmpty())
        {
            return ApiResponse::error([
                'error' => "No Subscripers in this Auction till now.",
            ]);
        }

        $subscripers = [];
        foreach($auctionSubscripers as $subscriper)
        {
           $subscripers[] = $subscriper->user()->first();
        }

            return ApiResponse::success([
                'subscripers' => UserResource::collection($subscripers)
            ],"subscripersAuction");
    }

    public function mysubscripe() 
    {
        $userId = Auth::id();

        $subscripAuctions = AuctionUser::where('user_id', $userId)->get();

        foreach ($subscripAuctions as $subscripAuction)
        {
            $Auctions = Auction::where('id', $subscripAuction->auction_id)->get();
        }

        if($Auctions->isNotEmpty())
        {
            return ApiResponse::success([
                'Auction' => AuctionResource::collection($Auctions),
            ]);
        }

        else {
            return ApiResponse::error('No subscripe Auctions.', 404);
        }
    }
}
