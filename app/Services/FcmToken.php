<?php
namespace App\Services;

use App\Models\FcmToken as ModelsFcmToken;

class FcmToken
{
    public function fcmSave($token, $userId)
    {
        $userFcmToken = ModelsFcmToken::where('token', $token)->where('token', $token)->first();

        if(!$userFcmToken)
        {
            //Notification_fireBase
            $fcmToken = ModelsFcmToken::create([
            'token' => $token,
            'user_id' => $userId,
            ]);
        }
    }
}