<?php

namespace App\Helpers;

class Helper
{


    public static function response($success, $message, $data = null, $status = 200)
    {

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if(!is_null($data)){
            $response['data'] = $data;
        }
        return response()->json($response, $status);
    }
}
