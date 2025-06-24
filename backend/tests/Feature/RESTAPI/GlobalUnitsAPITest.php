<?php

namespace Tests\Feature\RESTAPI;

use App\Models\GlobalUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\GlobalUnitsTestHelper;
use Tests\Helpers\ResponseTestHelper;
use Tests\TestCase;

class GlobalUnitsAPITest extends TestCase {
    use RefreshDatabase;
    use WithFaker;

    public const GLOBAL_UNITS_ENDPOINT = '/api/global-unit';


    public function setUp(): void {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user1_token = $this->user1->createToken('auth_token')->plainTextToken;
        $this->user2_token = $this->user2->createToken('auth_token')->plainTextToken;

        $this->globalUnitData = GlobalUnitsTestHelper::generateGlobalUnitData($this->faker);
    }

    public function createGlobalUnit(User $user, $data = []) {
        if($data === [])
            $data = GlobalUnitsTestHelper::generateGlobalUnitData($this->faker);

        return $this->actingAs($user)
            ->postJson(self::GLOBAL_UNITS_ENDPOINT, $data);
    }

    public function test_user_can_create_global_unit_using_all_fields() {
        $response = $this->createGlobalUnit($this->user1, $this->globalUnitData);

        $response->assertStatus(201);
        $response->assertJson(ResponseTestHelper::getSuccessCreateResponse($this->globalUnitData));
    }

    public function test_user_can_get_all_global_units() {
        $response = $this->actingAs($this->user1)
            ->getJson(self::GLOBAL_UNITS_ENDPOINT);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse(GlobalUnit::all()->toArray()));

    }

    public function test_user_can_get_specific_global_unit() {
        $globalUnit = $this->createGlobalUnit($this->user1, $this->globalUnitData);
        $globalUnitID = $globalUnit->json()['data']['id'];

        $response = $this->actingAs($this->user1)
            ->getJson(self::GLOBAL_UNITS_ENDPOINT . '/' . $globalUnitID);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse($this->globalUnitData));
    }

    public function test_user_can_edit_global_unit() {
        $globalUnit = $this->createGlobalUnit($this->user1, $this->globalUnitData);
        $globalUnitID = $globalUnit->json()['data']['id'];
        $globalUnitData = GlobalUnitsTestHelper::generateGlobalUnitData($this->faker);

        $response = $this->actingAs($this->user1)
            ->putJson(self::GLOBAL_UNITS_ENDPOINT . '/' . $globalUnitID, $globalUnitData);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessUpdateResponse($globalUnitData));

    }

    public function test_user_can_delete_global_unit() {
        $globalUnit = $this->createGlobalUnit($this->user1, $this->globalUnitData);
        $globalUnitID = $globalUnit->json()['data']['id'];

        $response = $this->actingAs($this->user1)
            ->deleteJson(self::GLOBAL_UNITS_ENDPOINT . '/' . $globalUnitID);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessDeleteResponse($this->globalUnitData));

        $this->assertDatabaseMissing('global_units', $this->globalUnitData);
    }

    public function test_when_user_is_updating_global_unit_default_it_remove_other_unit_default_flag() {
        $defGlobalUnit = GlobalUnit::where('default', true)->get();

        $this->assertCount(1, $defGlobalUnit);
        $resultData = $this->createGlobalUnit($this->user1, GlobalUnitsTestHelper::generateGlobalUnitData($this->faker,true));

        $newDefGlobalUnit = GlobalUnit::where('default', true)->get();
        $newDefID = $newDefGlobalUnit[0]['id'];

        $this->assertCount(1, $newDefGlobalUnit);
        $this->assertEquals($resultData->json()['data']['id'], $newDefID);

    }


}
