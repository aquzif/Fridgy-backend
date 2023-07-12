<?php

namespace Tests\Feature;

use App\Models\GlobalUnit;
use App\Models\ShoppingList;
use App\Models\User;
use App\Utils\Test\GlobalUnitsTestHelper;
use App\Utils\Test\ResponseTestHelper;
use App\Utils\Test\ShoppingListEntriesTestHelper;
use App\Utils\Test\ShoppingListTestHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShoppingListRawProductEntriesAPITest extends TestCase {

    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->global_unit = GlobalUnit::create(
            GlobalUnitsTestHelper::generateGlobalUnitData($this->faker,true)
        );
        $this->another_global_unit = GlobalUnit::create(
            GlobalUnitsTestHelper::generateGlobalUnitData($this->faker)
        );

        $this->shoppingList = ShoppingList::create(
            [
                ...ShoppingListTestHelper::generateRandomShoppingListData($this->faker),
                'user_id' => $this->user1->id
            ]
        );
    }



    public function createShoppingListEntry(User $user,ShoppingList $shoppingList, $data = []): \Illuminate\Testing\TestResponse {
        if($data == [])
            $data = ShoppingListEntriesTestHelper::generateRandomRawProductShoppingListEntryData($this->faker);

        $url = '/api/shopping-list/'.$shoppingList->id.'/entry';

        return $this->actingAs($user)
            ->postJson($url, $data);
    }


    public function test_user_can_add_raw_entry_to_shopping_list(): void {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawProductShoppingListEntryData($this->faker);
        $response = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $response->assertStatus(201);
        $response->assertJson(ResponseTestHelper::getSuccessCreateResponse($entryData));

    }

    public function test_user_can_see_every_entry_in_shopping_list(): void {

        $shoppingListID = $this->shoppingList->id;
        $entryData1 = ShoppingListEntriesTestHelper::generateRandomRawProductShoppingListEntryData($this->faker);
        $entryData2 = ShoppingListEntriesTestHelper::generateRandomRawProductShoppingListEntryData($this->faker);

        $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData1);
        $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData2);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry';
        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJsonCount(2,'data');
        $response->assertJsonFragment($entryData1);
        $response->assertJsonFragment($entryData2);

        $url= '/api/shopping-list/'.$shoppingListID;

        $response = $this->actingAs($this->user1)->getJson($url);
        $response->assertJsonCount(2,'data.entries');
    }


    public function test_user_can_see_entry_by_id() {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawProductShoppingListEntryData($this->faker);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['data']['id'];
        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse($entryData));

    }

    public function test_user_can_remove_entry_from_shopping_list() {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawProductShoppingListEntryData($this->faker);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['data']['id'];
        $response = $this->actingAs($this->user1)->deleteJson($url);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessDeleteResponse());

        $response = $this->actingAs($this->user1)->getJson($url);
        $response->assertStatus(404);

    }

    public function test_user_can_update_entry_in_shopping_list() {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawProductShoppingListEntryData($this->faker,$this->global_unit['id']);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);
        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['data']['id'];

        $entryData['unit_id'] = $this->another_global_unit['id'];
        $entryData['amount'] += 10;

        $response = $this->actingAs($this->user1)->putJson($url,$entryData);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessUpdateResponse($entryData));

        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse($entryData));

    }



}
