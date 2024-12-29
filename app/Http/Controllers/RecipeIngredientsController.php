<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class RecipeIngredientsController extends Controller {

    public function index(Recipe $recipe) {

        return ResponseUtils::generateSuccessResponse($recipe->ingredients()->get());
    }

    public function store(Recipe $recipe,Request $request) {
        $fields = $request->validate([
            'product_id' => 'required|numeric|exists:products,id',
            'product_unit_id' => 'required|numeric|exists:product_units,id',
            'amount_in_unit' => 'required|numeric',
        ]);

        $newObj = $recipe->ingredients()->create($fields);

        return ResponseUtils::generateSuccessResponse(
            $newObj->where('id',$newObj['id'])->first()
            ,'OK',201);
    }

    public function show(Recipe $recipe, Ingredient $ingredient) {
        if ($ingredient->recipe_id != $recipe->id) {
            return ResponseUtils::generateErrorResponse('Not found',404);
        }
        return ResponseUtils::generateSuccessResponse($ingredient);
    }

    public function update(Request $request, Recipe $recipe, Ingredient $ingredient) {
        if ($ingredient->recipe_id != $recipe->id) {
            return ResponseUtils::generateErrorResponse('Not found',404);
        }
        $fields = $request->validate([
            'product_id' => 'numeric|exists:products,id',
            'product_unit_id' => 'numeric|exists:product_units,id',
            'amount_in_unit' => 'numeric',
        ]);

        $ingredient->update($fields);
    }

    public function destroy(Recipe $recipe, Ingredient $ingredient) {
        $ingredient->delete();
    }


}
