<?php

namespace Tests\Feature;

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelpers\ShoppingListEntriesTestHelper;
use Tests\TestHelpers\ShoppingListTestHelper;

class ShoppingListEntriesAPITest extends TestCase {

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
            $data = ShoppingListEntriesTestHelper::generateRandomShoppingListEntryData($this->faker);

        $url = '/api/shopping-list/'.$shoppingList->id.'/entry';

        return $this->actingAs($user)
            ->postJson($url, $data);
    }

    public function test_user_can_add_raw_entry_to_shopping_list(): void {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomShoppingListEntryData($this->faker);
        $response = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $response->assertStatus(201);
        $response->assertJson($entryData);

    }

    public function test_user_can_see_every_entry_in_shopping_list(): void {

        $shoppingListID = $this->shoppingList->id;
        $entryData1 = ShoppingListEntriesTestHelper::generateRandomShoppingListEntryData($this->faker);
        $entryData2 = ShoppingListEntriesTestHelper::generateRandomShoppingListEntryData($this->faker);

        $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData1);
        $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData2);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry';
        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment($entryData1);
        $response->assertJsonFragment($entryData2);

    }

    public function test_user_can_see_entry_by_id() {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomShoppingListEntryData($this->faker);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['id'];
        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJson($entryData);

    }

    public function test_user_can_remove_entry_from_shopping_list() {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomShoppingListEntryData($this->faker);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['id'];
        $response = $this->actingAs($this->user1)->deleteJson($url);

        $response->assertStatus(200);

    }

    public function test_user_can_update_entry_in_shopping_list() {

        $shoppingListID = $this->shoppingList->id;
        $entryData = ShoppingListEntriesTestHelper::generateRandomShoppingListEntryData($this->faker);

        $entry = $this->createShoppingListEntry($this->user1,$this->shoppingList,$entryData);

        $url = '/api/shopping-list/'.$shoppingListID.'/entry/'.$entry->json()['id'];

        $entryData['product_name'] .= 'new';
        $entryData['amount'] += 10;
        $entryData['unit_name'] .= 'new';

        $response = $this->actingAs($this->user1)->putJson($url,$entryData);

        $response->assertStatus(200);
        $response->assertJson($entryData);

        $response = $this->actingAs($this->user1)->getJson($url);

        $response->assertStatus(200);
        $response->assertJson($entryData);

    }



}
