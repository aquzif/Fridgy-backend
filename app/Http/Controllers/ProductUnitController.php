<?php

    namespace App\Http\Controllers;

    use App\Models\Product;
    use App\Models\ProductUnit;
    use App\Utils\ResponseUtils;
    use Illuminate\Http\Request;

    class ProductUnitController extends Controller {

        public function __construct() {
            $this->authorizeResource(ProductUnit::class, ['productUnit','product']);
        }


        public function index(Product $product) {
            return ResponseUtils::generateSuccessResponse(ProductUnit::all());
        }

        public function store(Product $product, Request $request) {
            $fields = $request->validate([
                'name' => 'required|string',
                'grams_per_unit' => 'required|int',
                'default' => 'required|boolean',
            ]);

            if($fields['default']) {
                ProductUnit::where('product_id', $product->id)->update(['default' => false]);
            }

            $fields['product_id'] = $product->id;

            return ResponseUtils::generateSuccessResponse(ProductUnit::create($fields),'OK',201);
        }

        public function show(Product $product, ProductUnit $productUnit) {
            return ResponseUtils::generateSuccessResponse($productUnit);
        }

        public function update(Product $product, Request $request, ProductUnit $productUnit) {
            $fields = $request->validate([
                'name' => 'string',
                'grams_per_unit' => 'int',
                'default' => 'boolean',
            ]);



            if($fields['default']){
                ProductUnit::where('product_id', $product->id)->update(['default' => false]);
                $product->default_unit_id = $productUnit->id;
                $productUnit->default = true;
            }

            if($productUnit->id === $product->default_unit_id){
                $product->default_unit_converter = $fields['grams_per_unit'] ?? $productUnit->grams_per_unit;
                $product->default_unit_name = $fields['name'] ?? $productUnit->name;
            }



            if(($fields['default'] ?? false) === false)
                unset($fields['default']);

            $productUnit->update($fields);
            $product->save();


            return ResponseUtils::generateSuccessResponse($productUnit);
        }

        public function destroy(Product $product, ProductUnit $productUnit) {
            $productUnit->delete();

            return ResponseUtils::generateSuccessResponse('Deleted successfully');
        }



    }
