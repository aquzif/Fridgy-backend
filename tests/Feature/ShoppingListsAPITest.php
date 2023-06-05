<?php

namespace Tests\Feature;

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelpers\ShoppingListTestHelper;

class ShoppingListsAPITest extends TestCase {

    use RefreshDatabase;
    use WithFaker;

    public const SHOPPING_LIST_ENDPOINT = '/api/shopping-list';


    public function setUp(): void {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user1_token = $this->user1->createToken('auth_token')->plainTextToken;
        $this->user2_token = $this->user2->createToken('auth_token')->plainTextToken;

        $this->shoppingListData1 = ShoppingListTestHelper::generateRandomShoppingListData($this->faker);

    }

    public function createShoppingList(User $user, $data = []): \Illuminate\Testing\TestResponse {
        if($data == [])
            $data = ShoppingListTestHelper::generateRandomShoppingListData($this->faker);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->actingAs($user)
            ->postJson(self::SHOPPING_LIST_ENDPOINT, $data);
    }

    public function test_user_can_create_shopping_list_using_all_available_params(){

        $response = $this->createShoppingList($this->user1,$this->shoppingListData1);

        $response->assertStatus(201);
        $response->assertJson($this->shoppingListData1);
    }

    public function test_user_can_view_shopping_list(){
        $shoppingListData  = $this->createShoppingList($this->user1,$this->shoppingListData1)->json();

        $url = self::SHOPPING_LIST_ENDPOINT.'/'.$shoppingListData['id'];

        $request = $this->actingAs($this->user1)
            ->getJson($url, $shoppingListData);

        $request->assertStatus(200);
        $request->assertJson($shoppingListData);

    }

    public function test_user_can_modify_shopping_list(): void{

        $shoppingListData  = $this->createShoppingList($this->user1,$this->shoppingListData1)->json();


        $shoppingListData['name'] = $shoppingListData['name'].'_new';
        $url = self::SHOPPING_LIST_ENDPOINT.'/'.$shoppingListData['id'];

        $request = $this->actingAs($this->user1)
            ->patchJson($url, $shoppingListData);

        $request->assertStatus(200);
        $request->assertJson($shoppingListData);

    }

    public function test_user_can_delete_shopping_list(): void{

        $shoppingListData = $this->createShoppingList($this->user1,$this->shoppingListData1)->json();

        $url = self::SHOPPING_LIST_ENDPOINT.'/'.$shoppingListData['id'];

        $request = $this->actingAs($this->user1)
            ->deleteJson($url);

        $request->assertStatus(200);

        $list = ShoppingList::find($shoppingListData['id']);
        $this->assertNull($list);

    }

    public function test_when_user_is_removing_shopping_list_it_removes_entries_as_well(): void{

       /* $shoppingListData = $this->createShoppingList($this->user1,$this->shoppingListData1)->json();

        $url = self::SHOPPING_LIST_ENDPOINT.'/'.$shoppingListData['id'];

        $request = $this->actingAs($this->user1)
            ->deleteJson($url);

        $request->assertStatus(200);

        $list = ShoppingList::find($shoppingListData['id']);
        $this->assertNull($list);*/

    }

    public function test_user_cannot_see_another_user_shopping_list(): void{

        $shoppingList = $this->createShoppingList($this->user1);

        $url = self::SHOPPING_LIST_ENDPOINT.'/'.$shoppingList['id'];
        $request = $this->actingAs($this->user2)->getJson($url);

        $request->assertStatus(403);
        $request->assertJson([
           'message' => 'This action is unauthorized.'
        ]);

    }

    public function test_user_cannot_modify_another_user_shopping_list(): void{
        $shoppingList = $this->createShoppingList($this->user1)->json();

        $url = self::SHOPPING_LIST_ENDPOINT.'/'.$shoppingList['id'];

        $shoppingList['name'] = $shoppingList['name'].'_new';

        $request = $this->actingAs($this->user2)->patchJson($url,$shoppingList);

        $request->assertStatus(403);
        $request->assertJson([
            'message' => 'This action is unauthorized.'
        ]);
    }

    public function test_user_cannot_delete_another_user_shopping_list(): void{
        $shoppingList = $this->createShoppingList($this->user1)->json();

        $url = self::SHOPPING_LIST_ENDPOINT.'/'.$shoppingList['id'];

        $request = $this->actingAs($this->user2)->deleteJson($url);

        $request->assertStatus(403);
        $request->assertJson([
            'message' => 'This action is unauthorized.'
        ]);
    }


}
