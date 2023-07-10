<?php

    namespace App\Http\Controllers;

    use App\Models\Product;
    use App\Models\ProductUnit;
    use App\Utils\ResponseUtils;
    use Illuminate\Http\Request;

    class ProductUnitController extends Controller {
        public function index(Product $product) {
            return ResponseUtils::generateSuccessResponse(ProductUnit::all());
        }

        public function store(Product $product, Request $request) {
            $fields = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'name' => 'required|string',
                'grams_per_unit' => 'required|string',
                'default' => 'required|boolean',
            ]);

            if($fields['default']) {
                ProductUnit::where('product_id', $fields['product_id'])->update(['default' => false]);
            }

            return response(ResponseUtils::generateSuccessResponse(ProductUnit::create($fields),'OK',201),201);
        }

        public function show(Product $product, ProductUnit $productUnit) {
            return ResponseUtils::generateSuccessResponse($productUnit);
        }

        public function update(Product $product, Request $request, ProductUnit $productUnit) {
            $fields = $request->validate([
                'name' => 'string',
                'grams_per_unit' => 'string',
                'default' => 'boolean',
            ]);

            $productUnit->update($fields);

            return ResponseUtils::generateSuccessResponse($productUnit);
        }

        public function destroy(Product $product, ProductUnit $productUnit) {
            $productUnit->delete();

            return ResponseUtils::generateSuccessResponse('Deleted successfully');
        }
    }
