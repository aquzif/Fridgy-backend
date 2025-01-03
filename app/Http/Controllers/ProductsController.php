<?php

    namespace App\Http\Controllers;

    use App\Models\Product;
    use App\Rules\StringableRule;
    use App\Utils\ResponseUtils;
    use Illuminate\Http\Request;

    class ProductsController extends Controller {

        public function __construct() {
            $this->authorizeResource(Product::class, 'product');
        }

        public function index(Request $request) {

            $fields = $request->validate([
                'perPage' => 'numeric'
            ]);

            return ResponseUtils::generateSuccessResponse(Product::all());
            if(!empty($fields['perPage']))
                return ResponseUtils::generateSuccessResponse(Product::paginate($fields['perPage']));
            else
                return ResponseUtils::generateSuccessResponse(Product::paginate(10));

        }

        public function search(Request $request) {
            return Product::search($request->input('query'))->get();
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
                'category_id' => 'numeric',
                'open_food_facts_id' => [new StringableRule],
                'barcode' => [new StringableRule],
                'barcode_type' => 'string',
            ]);

            if(isset($fields['category_id']) && $fields['category_id'] == 0)
                unset($fields['category_id']);

            $newObj = Product::create($fields);

            $newObj = $newObj->where('id',$newObj['id'])->first();

            return ResponseUtils::generateSuccessResponse(
                $newObj
            ,'OK',201);
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
                'category_id' => 'numeric',
                'open_food_facts_id' => [new StringableRule],
                'barcode' => [new StringableRule],
                'barcode_type' => 'string',
            ]);

            if(isset($fields['category_id']) && $fields['category_id'] == 0){
                $product->productCategory()->dissociate();
                unset($fields['category_id']);
            }

            if(isset($fields['default_unit_id'])){

                $unit = $product->units()->where('id',$fields['default_unit_id'])
                    ->where('product_id',$product['id'])->first();

                if(!$unit)
                    return ResponseUtils::generateErrorResponse('Unit not found',404);

                $product->units()->update(['default' => false]);
                $product->units()->where('id',$fields['default_unit_id'])->update(['default' => true]);
                $fields['default_unit_converter'] = $unit['grams_per_unit'];
                $fields['default_unit_name'] = $unit['name'];
            }

            $product->update($fields);

            return ResponseUtils::generateSuccessResponse($product);
        }

        public function destroy(Product $product) {
            $product->delete();

            return ResponseUtils::generateSuccessResponse('Deleted successfully');
        }
    }
