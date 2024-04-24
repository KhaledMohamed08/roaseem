<?php

namespace App\Http\Controllers\API\Block;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function blockUser(User $user)
    {
        $blocker = Auth::user();
        $blocked = $user;
        if ($blocker->id != $blocked->id) {
            $block = Block::where('blocker_id', $blocker->id)->where('blocked_id', $blocked->id)->first();
            if ($block) {
                return ApiResponse::error(
                    'User Already Blocked!',
                    400
                );
            }

            $block = Block::create([
                'blocker_id' => $blocker->id,
                'blocked_id' => $blocked->id
            ]);

            return ApiResponse::success(
                [
                    'block' => $block,
                ],
                'Blocked Successfuly',
                200
            );

        } else {
            return ApiResponse::error(
                'You Can Not Block Yourself!!!',
                400
            );
        }
    }

    public function unblockUser(User $user)
    {
        $block = Block::where('blocked_id', $user->id)->where('blocker_id', auth()->id())->first();
        if (!$block) {
            return ApiResponse::error(
                'User Not Blocked yet!',
                400
            );
        }
        $block->delete();

        return ApiResponse::success(
            [
                'block' => $block,
            ],
            'Unblocked Successfully',
            200
        );
    }
}
