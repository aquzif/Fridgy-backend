<?php

namespace Tests\Feature\UtilsTests;

use App\Utils\ResponseUtils;
use Tests\TestCase;

class ResponseUtilsTest extends TestCase {

    public function test_generateSuccessResponse_with_data(): void {
        //dd('https://stackoverflow.com/questions/41266764/target-illuminate-contracts-routing-responsefactory-is-not-instantiable/61999395#61999395');
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

        $this->assertEquals($code,$actual->status());
        $this->assertEquals($expected,$actual->original);
    }

   public function test_generateSuccessResponse_without_data(): void {
        $message = 'OK';
        $code = 200;

        $expected = [
            'code' => $code,
            'message' => $message
        ];

        $actual = ResponseUtils::generateSuccessResponse(null,$message,$code);

       $this->assertEquals($code,$actual->status());
       $this->assertEquals($expected,$actual->original);
    }

    public function test_generateErrorResponse(): void {
        $message = 'Error';
        $code = 400;

        $expected = [
            'code' => $code,
            'message' => $message
        ];

        $actual = ResponseUtils::generateErrorResponse($message,$code);

        $this->assertEquals($code,$actual->status());
        $this->assertEquals($expected,$actual->original);
    }

}
