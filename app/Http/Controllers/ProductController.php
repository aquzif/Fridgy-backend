<?php

    namespace App\Http\Controllers;

    use App\Models\Product;
    use App\Utils\ResponseUtils;
    use Illuminate\Http\Request;

    class ProductController extends Controller {
        public function index() {
            return ResponseUtils::generateSuccessResponse(Product::all());
        }

        public function store(Request $request) {
            $fields = $request->validate([
                'name' => 'string|required',
                'nutrition_energy_kcal' => 'required|numeric',
                'nutrition_energy_kj' => 'required|numeric',
                'nutrition_carbs' => 'required|numeric',
                'nutrition_fat' => 'required|numeric',
                'nutrition_sugar' => 'required|numeric',
                'nutrition_protein' => 'required|numeric',
                'nutrition_salt' => 'required|numeric',
            ]);


            $newObj = Product::create($fields);

            $newObj = $newObj->where('id',$newObj['id'])->first();

            return response(ResponseUtils::generateSuccessResponse(
                $newObj
            ,'OK',201),201);
        }

        public function show(Product $product) {
            return ResponseUtils::generateSuccessResponse($product);
        }

        public function update(Request $request, Product $product) {
            $fields = $request->validate([
                'name' => 'string',
                'nutrition_energy_kcal' => 'numeric',
                'nutrition_energy_kj' => 'numeric',
                'nutrition_carbs' => 'numeric',
                'nutrition_fat' => 'numeric',
                'nutrition_sugar' => 'numeric',
                'nutrition_protein' => 'numeric',
                'nutrition_salt' => 'numeric',
                'default_unit_id' => 'numeric',
            ]);

            if(isset($fields['default_unit_id'])){
                $product->units()->update(['default' => false]);
                $product->units()->where('id',$fields['default_unit_id'])->update(['default' => true]);
            }

            $product->update($fields);

            return ResponseUtils::generateSuccessResponse($product);
        }

        public function destroy(Product $product) {
            $product->delete();

            return ResponseUtils::generateSuccessResponse('Deleted successfully');
        }
    }
