<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Message;

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
            ->with(['sender', 'receiver'])
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
}
