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

        return $toReturn;
    }

    public static function generateErrorResponse($message = 'Error',$code = 400) {
        return [
            'code' => $code,
            'message' => $message
        ];
    }

}
