<?php

namespace App\Utils;

class ResponseUtils {

    public static function generateSuccessResponse($data = null,$message = 'OK',$code = 200) {
        $toReturn = [
            'code' => $code,
            'message' => $message
        ];
        if($data != null)
            $toReturn['data'] = $data;

        return response($toReturn,$code);
    }

    public static function generateErrorResponse($message = 'Error',$code = 400) {
        return response([
            'code' => $code,
            'message' => $message
        ],$code);
    }

}
