<?php

namespace Tests\Feature;

use App\Models\User;
use Feature\TestHelpers\ProductsTestHelper;
use Feature\TestHelpers\ResponseTestHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsAPI extends TestCase {

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

    public function test_user_can_create_product_using_all_available_params(){

        $response = $this->createProduct($this->user1,$this->productData);

        $this->productData['entries'] = [];
        $response->assertStatus(201);
        $response->assertJson(ResponseTestHelper::getSuccessCreateResponse($this->productData));
    }
}
