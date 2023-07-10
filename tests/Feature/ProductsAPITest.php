<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Feature\TestHelpers\ProductsTestHelper;
use Feature\TestHelpers\ProductUnitsTestHelper;
use Feature\TestHelpers\ResponseTestHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $response->assertJson(ResponseTestHelper::getSuccessGetResponse([$this->productData]));
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

    //test product caanot update another product unit default state
    //test product will throw error is try to access another product unit
    //test product unit cannot update another product unit
    //test that by changing default unit for product, there is updating name and grams_prt_unit in the product too
    //test when user is deleting product, all product units are deleted too

}
