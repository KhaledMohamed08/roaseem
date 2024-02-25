<?php

// app/Http/Responses/ApiResponse.php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($data = null, $message = null, $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $status);
    }

    public static function error($message = null, $status = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }

    public static function successWithoutData($message = null, $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }
}
