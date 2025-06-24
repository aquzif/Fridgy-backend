<?php

namespace Tests\Feature\RESTAPI;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\ProductsTestHelper;
use Tests\Helpers\ProductUnitsTestHelper;
use Tests\Helpers\ResponseTestHelper;
use Tests\TestCase;

class ProductsAPITest extends TestCase {
    use RefreshDatabase;
    use WithFaker;

    public const PRODUCT_ENDPOINT = '/api/product';

    public function setUp(): void {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user1_token = $this->user1->createToken('auth_token')->plainTextToken;
        $this->user2_token = $this->user2->createToken('auth_token')->plainTextToken;

        $this->productData = ProductsTestHelper::generateProductData($this->faker);

    }

    public function createProduct(User $user, $data = []) {
        if($data == [])
            $data = ProductsTestHelper::generateProductData($this->faker);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->actingAs($user)
            ->postJson(self::PRODUCT_ENDPOINT, $data);
    }

    public function createUnitForProduct($productID, $data = []) {
        if($data == [])
            $data = ProductUnitsTestHelper::generateProductUnitData($this->faker);

        return $this->actingAs($this->user1)
            ->postJson(self::PRODUCT_ENDPOINT . '/' . $productID . '/unit', $data);

    }

    //-------------------------------------
    //  Only Product tests
    //-------------------------------------

    public function test_user_can_create_product_using_all_available_params(){
        $response = $this->createProduct($this->user1,$this->productData);

        $this->productData['units'] = [];
        $response->assertStatus(201);
        $response->assertJson(ResponseTestHelper::getSuccessCreateResponse($this->productData));
    }

    public function test_user_can_fetch_product_by_id() {
        $response = $this->createProduct($this->user1,$this->productData);
        $response->assertStatus(201);

        $product_id = $response->json('data.id');

        $response = $this->actingAs($this->user1)
            ->getJson(self::PRODUCT_ENDPOINT . '/' . $product_id);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse($this->productData));
    }

    public function test_user_can_fetch_all_products() {
        $response = $this->createProduct($this->user1,$this->productData);
        $response->assertStatus(201);

        $response = $this->actingAs($this->user1)
            ->getJson(self::PRODUCT_ENDPOINT);

        $response->assertStatus(200);

        $response->assertJson(ResponseTestHelper::getSuccessGetResponse([
            $this->productData
        ]));
    }

    public function test_user_can_update_product() {
        $product = $this->createProduct($this->user1,$this->productData);

        $product_id = $product->json('data.id');

        $productData = ProductsTestHelper::generateProductData($this->faker);

        $response = $this->actingAs($this->user1)
            ->putJson(self::PRODUCT_ENDPOINT . '/' . $product_id, $productData);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessUpdateResponse($productData));
    }

    public function test_user_can_delete_product() {
        $product = $this->createProduct($this->user1,$this->productData);

        $product_id = $product->json('data.id');

        $response = $this->actingAs($this->user1)
            ->deleteJson(self::PRODUCT_ENDPOINT . '/' . $product_id);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessDeleteResponse());

        $this->assertEmpty(Product::all());
    }

    //-------------------------------------
    //  Product Units tests
    //-------------------------------------

    public function test_that_created_product_has_default_unit() {
        $product = $this->createProduct($this->user1,$this->productData);
        $product_id = $product->json('data.id');

        $response = $this->actingAs($this->user1)
            ->getJson(self::PRODUCT_ENDPOINT . '/' . $product_id);

        $response->assertStatus(200);
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse($this->productData));

        $response = $response->json()['data'];

        $units = $response['units'];

        $this->assertCount(1,$units);
        $this->assertEquals($units[0]['id'], $response['default_unit_id']);
    }

    public function test_user_can_change_default_unit() {
        $product = $this->createProduct($this->user1,$this->productData);

        $product_id = $product->json('data.id');

        $response = $this->actingAs($this->user1)
            ->getJson(self::PRODUCT_ENDPOINT . '/' . $product_id . '/unit');

        $anotherUnit = $this->createUnitForProduct($product_id)->json('data');

        $response = $this->actingAs($this->user1)->putJson(self::PRODUCT_ENDPOINT . '/' . $product_id,[
            'default_unit_id' => $anotherUnit['id']
        ]);

        $resData = $response->json()['data'];

        $this->assertEquals($resData['default_unit_id'], $anotherUnit['id']);
    }

    public function test_product_will_throw_error_when_try_to_access_on_another_product_unit() {
        $product = $this->createProduct($this->user1,$this->productData);
        $product2 = $this->createProduct($this->user1,$this->productData);
        $anotherProductUnitID = $product2->json('data.units.0.id');

        $product_id = $product->json('data.id');
        $response = $this->actingAs($this->user1)
            ->getJson(self::PRODUCT_ENDPOINT . '/' . $product_id . '/unit/' . $anotherProductUnitID);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'This action is unauthorized.'
        ]);

    }

    public function test_product_will_throw_error_when_try_to_update_another_product_unit() {
        $product = $this->createProduct($this->user1,$this->productData);
        $product2 = $this->createProduct($this->user1,$this->productData);
        $anotherProductUnitID = $product2->json('data.units.0.id');

        $product_id = $product->json('data.id');
        $response = $this->actingAs($this->user1)
            ->putJson(self::PRODUCT_ENDPOINT . '/' . $product_id . '/unit/' . $anotherProductUnitID,[
                'name' => 'test'
            ]);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'This action is unauthorized.'
        ]);

    }

    public function test_that_by_changing_default_unit_there_are_changing_its_properties_too() {
        $product = $this->createProduct($this->user1,$this->productData);
        $newDefaultUnit = $this->createUnitForProduct($product->json('data.id'))->json('data');


        $updatedProduct = $this->actingAs($this->user1)
            ->putJson(self::PRODUCT_ENDPOINT . '/' . $product->json('data.id'),[
                'default_unit_id' => $newDefaultUnit['id']
            ]);

        $updatedProduct->assertJson([
            'data' => [
                'default_unit_id' => $newDefaultUnit['id'],
                'default_unit_name' => $newDefaultUnit['name'],
                'default_unit_converter' => $newDefaultUnit['grams_per_unit']
            ]
        ]);

    }

    public function test_when_product_is_deleted_its_units_are_deleted_too() {
        $product = $this->createProduct($this->user1,$this->productData);
        $this->createUnitForProduct($product->json('data.id'))->json('data');

        $this->assertEquals(2,ProductUnit::count());

        $this->actingAs($this->user1)
            ->delete(self::PRODUCT_ENDPOINT . '/' . $product->json('data.id'));

        $this->assertEquals(0,ProductUnit::count());

    }

    public function test_cannot_assign_another_unit_as_default_to_non_related_product() {

        $product1 = $this->createProduct($this->user1,$this->productData);
        $product2 = $this->createProduct($this->user1,$this->productData);
        $newDefaultUnit = $this->createUnitForProduct($product2->json('data.id'))->json('data');

        $result = $this->actingAs($this->user1)
            ->putJson(self::PRODUCT_ENDPOINT . '/' . $product1->json('data.id'),[
                'default_unit_id' => $newDefaultUnit['id']
            ]);

        $result->assertJson([
            'code' => 404,
            'message' => 'Unit not found'
        ]);
        $result->assertStatus(404);

    }


}
