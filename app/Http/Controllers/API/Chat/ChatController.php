<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validate input
        $request->validate([
            'receiver_id' => 'required',
            'message' => 'required',
        ]);

        // Create message
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
        
        // return response()->json(['message' => 'Message sent successfully', 'data' => $message]);
        return ApiResponse::success(
            [
                'message' => $message,
            ],
            'Message Sent successfully',
            200,
        );
    }

    public function getMessages()
    {
        // Fetch messages
        $messages = Message::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            // ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // return response()->json(['messages' => $messages]);
        return ApiResponse::success(
            [
                'messages' => $messages,
            ],
            'All Messages',
            200
        );
    }

    public function getChats()
    {
        $users = [];
        // Fetch messages
        $messages = Message::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            // ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($messages as $message) {
            $user = User::find($message->receiver_id);
            if (!in_array($user, $users)) {
                array_push($users, $user);
            }
        }
        return $users;
        // return response()->json(['messages' => $messages]);
        return ApiResponse::success(
            [
                'messages' => $messages,
            ],
            'All Messages',
            200
        );
    }

    public function blockToggle(User $user)
    {
        $authUser = Auth::user();

    }
}
