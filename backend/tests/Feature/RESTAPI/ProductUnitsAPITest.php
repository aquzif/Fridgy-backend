<?php

namespace RESTAPI;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers\ProductsTestHelper;
use Tests\Helpers\ProductUnitsTestHelper;
use Tests\TestCase;

class ProductUnitsAPITest extends TestCase {
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void {
        parent::setUp();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user1_token = $this->user1->createToken('auth_token')->plainTextToken;
        $this->user2_token = $this->user2->createToken('auth_token')->plainTextToken;

        $this->product = Product::create(ProductsTestHelper::generateProductData($this->faker));
    }

    public function createProductUnit($productID, $data = []) {
        if($data == [])
            $data = ProductUnitsTestHelper::generateProductUnitData($this->faker);

        return $this->actingAs($this->user1)
            ->postJson('/api/product/' . $productID . '/unit', $data);

    }

    public function test_user_can_create_unit_for_product(){

        $data = ProductUnitsTestHelper::generateProductUnitData($this->faker);

        $response = $this->createProductUnit($this->product->id,$data);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                ...$data
            ]
        ]);
        //2 because product have default unit
        $this->assertEquals(2,ProductUnit::count());

    }

    public function test_user_can_update_unit_inside_product(){
        $productUnit = $this->createProductUnit($this->product['id'],ProductUnitsTestHelper::generateProductUnitData($this->faker))
            ->json()['data'];
        $newData = ProductUnitsTestHelper::generateProductUnitData($this->faker);

        $response = $this->actingAs($this->user1)
            ->putJson('/api/product/' . $productUnit['product_id'] . '/unit/' . $productUnit['id'], $newData);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                ...$newData
            ]
        ]);

        $product = Product::find($productUnit['product_id']);



    }

    public function test_user_can_delete_unit_from_product() {
        $productUnit = $this->createProductUnit($this->product['id'],ProductUnitsTestHelper::generateProductUnitData($this->faker))
            ->json()['data'];

        $response = $this->actingAs($this->user1)
            ->deleteJson('/api/product/' . $productUnit['product_id'] . '/unit/' . $productUnit['id']);

        $response->assertStatus(200);
        $this->assertEquals(1,ProductUnit::count());
    }

    public function test_user_cannot_delete_default_unit() {

        $unitID = $this->product->units[0]['id'];

        $response = $this->actingAs($this->user1)
            ->deleteJson('/api/product/' . $this->product['id'] . '/unit/' . $unitID);

        $response->assertStatus(403);
        $this->assertEquals(1,ProductUnit::count());

    }

    public function test_user_cannot_set_default_unit_to_false() {

        $unitID = $this->product->units[0]['id'];

        $this->actingAs($this->user1)
            ->putJson('/api/product/' . $this->product['id'] . '/unit/' . $unitID, ['default' => false]);

        $product = Product::find($this->product['id']);

        $this->assertTrue($product->units[0]['default']);
        $this->assertEquals($this->product->units[0],json_encode($product->units[0]));

    }

    public function test_when_user_is_updating_default_unit_its_updating_fields_on_product() {

        $newData = ProductUnitsTestHelper::generateProductUnitData($this->faker);
        $productUnit = $this->product->units[0];

        $this->actingAs($this->user1)
            ->putJson('/api/product/' . $this->product['id'] . '/unit/' . $productUnit['id'], $newData);

        $product = Product::find($this->product['id']);
        $productUnit = $product->units[0];

        $this->assertEquals([
            'default_unit_id' => $productUnit['id'],
            'default_unit_converter' => $newData['grams_per_unit'],
            'default_unit_name' => $newData['name'],
        ],[
            'default_unit_id' => $product['default_unit_id'],
            'default_unit_converter' => $product['default_unit_converter'],
            'default_unit_name' => $product['default_unit_name'],
        ]);
    }



}
