<?php

namespace App\Http\Controllers\API\chat;

use App\Http\Controllers\Controller;
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

        // Optionally broadcast an event for real-time updates

        return response()->json(['message' => 'Message sent successfully', 'data' => $message]);
    }

    public function getMessages(Request $request)
    {
        // Fetch messages
        $messages = Message::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['messages' => $messages]);
    }
}
