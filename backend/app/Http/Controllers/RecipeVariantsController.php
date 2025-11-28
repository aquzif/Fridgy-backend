<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeVariant;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class RecipeVariantsController extends Controller
{
    public function index(Recipe $recipe)
    {
        return ResponseUtils::generateSuccessResponse($recipe->variants()->get());
    }

    public function store(Request $request, Recipe $recipe)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'is_default' => 'boolean',
        ]);

        $variant = $recipe->variants()->create([
            'name' => $fields['name'],
            'is_default' => $fields['is_default'] ?? false,
            'calories_per_serving' => $recipe->calories_per_serving,
        ]);

        if ($variant->is_default) {
            $recipe->variants()->where('id', '!=', $variant->id)->update(['is_default' => false]);
            $recipe->calories_per_serving = $variant->calories_per_serving;
            $recipe->saveQuietly();
        }

        return ResponseUtils::generateSuccessResponse($variant, 'OK', 201);
    }

    public function show(Recipe $recipe, RecipeVariant $recipeVariant)
    {
        if ($recipeVariant->recipe_id !== $recipe->id) {
            return ResponseUtils::generateErrorResponse('Not found', 404);
        }

        return ResponseUtils::generateSuccessResponse($recipeVariant);
    }

    public function update(Request $request, Recipe $recipe, RecipeVariant $recipeVariant)
    {
        if ($recipeVariant->recipe_id !== $recipe->id) {
            return ResponseUtils::generateErrorResponse('Not found', 404);
        }

        $fields = $request->validate([
            'name' => 'string',
            'is_default' => 'boolean',
        ]);

        $recipeVariant->update($fields);

        if (isset($fields['is_default']) && $fields['is_default']) {
            $recipe->variants()->where('id', '!=', $recipeVariant->id)->update(['is_default' => false]);
            $recipe->calories_per_serving = $recipeVariant->calories_per_serving;
            $recipe->saveQuietly();
        }

        return ResponseUtils::generateSuccessResponse($recipeVariant);
    }

    public function destroy(Recipe $recipe, RecipeVariant $recipeVariant)
    {
        if ($recipeVariant->recipe_id !== $recipe->id) {
            return ResponseUtils::generateErrorResponse('Not found', 404);
        }

        if ($recipe->variants()->count() <= 1) {
            return ResponseUtils::generateErrorResponse('Cannot remove last variant', 400);
        }

        $wasDefault = $recipeVariant->is_default;
        $recipeVariant->delete();

        if ($wasDefault) {
            $newDefault = $recipe->variants()->first();
            if ($newDefault) {
                $newDefault->is_default = true;
                $newDefault->saveQuietly();
                $recipe->calories_per_serving = $newDefault->calories_per_serving;
                $recipe->saveQuietly();
            }
        }

        return ResponseUtils::generateSuccessResponse('OK');
    }
}
