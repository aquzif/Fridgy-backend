<?php

namespace Tests\Unit\UtilsTests;

use App\Utils\ResponseUtils;
use PHPUnit\Framework\TestCase;

class ResponseUtilsTest extends TestCase {

    public function test_generateSuccessResponse_with_data(): void {
        $data = [
            'test' => 'test'
        ];
        $message = 'OK';
        $code = 200;

        $expected = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        $actual = ResponseUtils::generateSuccessResponse($data,$message,$code);

        $this->assertEquals($expected,$actual);
    }

    public function test_generateSuccessResponse_without_data(): void {
        $message = 'OK';
        $code = 200;

        $expected = [
            'code' => $code,
            'message' => $message
        ];

        $actual = ResponseUtils::generateSuccessResponse(null,$message,$code);

        $this->assertEquals($expected,$actual);
    }

    public function test_generateErrorResponse(): void {
        $message = 'Error';
        $code = 400;

        $expected = [
            'code' => $code,
            'message' => $message
        ];

        $actual = ResponseUtils::generateErrorResponse($message,$code);

        $this->assertEquals($expected,$actual);
    }

}
