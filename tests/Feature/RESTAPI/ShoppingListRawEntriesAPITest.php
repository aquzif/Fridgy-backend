<?php

namespace Tests\Feature\RESTAPI;

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\ResponseTestHelper;
use Tests\Helpers\ShoppingListEntriesTestHelper;
use Tests\Helpers\ShoppingListTestHelper;
use Tests\TestCase;

class ShoppingListRawEntriesAPITest extends TestCase {

    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->shoppingList = ShoppingList::create(
            [
                ...ShoppingListTestHelper::generateRandomShoppingListData($this->faker),
                'user_id' => $this->user1->id
            ]
        );
    }



    public function createShoppingListEntry(User $user,ShoppingList $shoppingList, $data = []): \Illuminate\Testing\TestResponse {
        if($data == [])
            $data = ShoppingListEntriesTestHelper::generateRandomRawShoppingListEntryData($this->faker);

        $url = '/api/shopping-list/'.$shoppingList->id.'/entry';

        return $this->actingAs($user)
            ->postJson($url, $data);
    }


    public function test_user_can_add_raw_entry_to_shopping_list(): void {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawShoppingListEntryData($this->faker);
        $response = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $response->assertStatus(201);
        $response->assertJson(ResponseTestHelper::getSuccessCreateResponse($entryData));

    }

    public function test_user_can_see_every_entry_in_shopping_list(): void {

        $shoppingListID = $this->shoppingList->id;
        $entryData1 = ShoppingListEntriesTestHelper::generateRandomRawShoppingListEntryData($this->faker);
        $entryData2 = ShoppingListEntriesTestHelper::generateRandomRawShoppingListEntryData($this->faker);

        $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData1);
        $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData2);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry';
        $response = $this->actingAs($this->user1)->getJson($url);

        $entryData1['category_id'] = null;
        $entryData2['category_id'] = null;

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
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawShoppingListEntryData($this->faker);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['data']['id'];
        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse($entryData));

    }

    public function test_user_can_remove_entry_from_shopping_list() {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawShoppingListEntryData($this->faker);

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
        $entryData = ShoppingListEntriesTestHelper::generateRandomRawShoppingListEntryData($this->faker);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['data']['id'];

        $entryData['product_name'] .= 'new';

        $response = $this->actingAs($this->user1)->putJson($url,$entryData);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessUpdateResponse($entryData));

        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse($entryData));

    }



}
