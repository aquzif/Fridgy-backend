<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class RecipesController extends Controller {

    public function index(Request $request) {
        return ResponseUtils::generateSuccessResponse(Recipe::all());
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'string|required',
            'prepare_time' => 'required|numeric',
            'serving_amount' => 'required|numeric',
            'image' => 'string',
            'steps' => 'json|required',
        ]);

        $newObj = Recipe::create($fields);

        return ResponseUtils::generateSuccessResponse(
            $newObj->where('id',$newObj['id'])->first()
            ,'OK',201);
    }

    public function show(Recipe $recipe) {
        return ResponseUtils::generateSuccessResponse($recipe);
    }

    public function update(Request $request, Product $product) {
        $fields = $request->validate([
            'name' => 'string',
            'prepare_time' => 'numeric',
            'serving_amount' => 'numeric',
            'image' => 'string',
            'steps' => 'json',
        ]);

        $product->update($fields);
    }

    public function destroy(Recipe $recipe) {
        $recipe->delete();
    }

}
