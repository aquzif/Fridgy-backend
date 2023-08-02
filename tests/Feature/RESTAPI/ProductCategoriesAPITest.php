<?php

namespace RESTAPI;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\ProductCategoriesTestHelper;
use Tests\TestCase;

class ProductCategoriesAPITest extends TestCase {
    use RefreshDatabase;
    use WithFaker;

    public const PRODUCT_ENDPOINT = '/api/product-category';

   protected function setUp(): void {
       parent::setUp();
       $this->user1 = User::factory()->create();
       $this->user2 = User::factory()->create();

       $this->user1_token = $this->user1->createToken('auth_token')->plainTextToken;
       $this->user2_token = $this->user2->createToken('auth_token')->plainTextToken;

       $this->categoryData = ProductCategoriesTestHelper::generateProductCategoryData($this->faker);
   }

    public function createCategory(User $user, $data = []) {
        if($data == [])
            $data = ProductCategoriesTestHelper::generateProductCategoryData($this->faker);

        return $this->actingAs($user)
            ->postJson(self::PRODUCT_ENDPOINT, $data);
    }


    //-------------------------------------
    //  Only ProductCategory tests
    //-------------------------------------

    public function test_user_can_list_all_categories() {
        $this->createCategory($this->user1,$this->categoryData);
        $this->createCategory($this->user1,$this->categoryData);

        $response = $this->actingAs($this->user1)
            ->getJson(self::PRODUCT_ENDPOINT);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
        $response->assertJsonCount(2, 'data');

    }

    public function test_user_can_see_specific_global_category() {
        $response = $this->createCategory($this->user1,$this->categoryData);
        $id = $response['data']['id'];

        $response = $this->actingAs($this->user1)
            ->getJson(self::PRODUCT_ENDPOINT . '/' . $id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at'
            ]
        ]);

        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => $this->categoryData['name']
            ]
        ]);
    }

    public function test_user_can_create_product_category_using_all_available_params(){
        $response = $this->createCategory($this->user1,$this->categoryData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at'
            ]
        ]);

        $this->assertDatabaseHas('product_categories', [
            'name' => $this->categoryData['name']
        ]);
    }

    public function test_user_can_update_product_category() {
        $response = $this->createCategory($this->user1,$this->categoryData);

        $categoryID = $response['data']['id'];
        $newCategoryData = ProductCategoriesTestHelper::generateProductCategoryData($this->faker);

        $response = $this->actingAs($this->user1)
            ->putJson(self::PRODUCT_ENDPOINT . '/' . $categoryID, $newCategoryData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at'
            ]
        ]);

        $this->assertDatabaseHas('product_categories', [
            'name' => $newCategoryData['name']
        ]);
    }

    public function test_user_can_delete_category() {
        $response = $this->createCategory($this->user1,$this->categoryData);
        $this->createCategory($this->user1,$this->categoryData);
        $id = $response['data']['id'];
        $this->assertDatabaseCount('product_categories', 2);
        $this->actingAs($this->user1)
            ->deleteJson(self::PRODUCT_ENDPOINT . '/' . $id);

        $this->assertDatabaseCount('product_categories', 1);
    }

}
