<?php

namespace Feature\TestHelpers;

class ResponseTestHelper {

    public static function getSuccessGetResponse($data) {
        return [
            'code' => 200,
            'message' => 'OK',
            'data' => $data
        ];
    }

    public static function getSuccessCreateResponse($data) {
        return [
            'code' => 201,
            'message' => 'OK',
            'data' => $data
        ];
    }

    public static function getSuccessUpdateResponse($data) {
        return [
            'code' => 200,
            'message' => 'OK',
            'data' => $data
        ];
    }

    public static function getErrorResponse($msg = 'Error') {
        return [
            'code' => 400,
            'message' => $msg
        ];
    }

    public static function getSuccessDeleteResponse() {
        return [
            'code' => 200,
            'message' => 'OK'
        ];
    }

}
