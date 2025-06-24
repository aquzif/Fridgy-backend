<?php

namespace App\Http\Controllers;

use App\Models\CalendarEntry;
use App\Models\FastFoodStore;
use App\Models\Recipe;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class CalendarEntriesController extends Controller
{
    public function index(Request $request) {

        $filters = $request->validate([
            'date_from' => 'date',
            'date_to' => 'date',
        ]);

        $user = $request->user();

        $req = $user->calendarEntries();

        if(isset($filters['date_from']))
            $req->when($filters['date_from'], fn($query, $date) => $query->where('date', '>=', $date));
        if(isset($filters['date_to']))
            $req->when($filters['date_to'], fn($query, $date) => $query->where('date', '<=', $date));

        $entries = $req->get();

        return ResponseUtils::generateSuccessResponse($entries);
    }


    public function store(Request $request) {

        $params = $request->validate([
            'type' => 'required|in:from_recipe,from_fast_food_store,from_ingredients,quick_entry',
            'meal_order' => 'integer|required',
            'date' => 'required|date',
        ]);

        $user = $request->user();

        //$user->calendarEntries()->where('date', $request['date'])->where('meal_order', $request['meal_order'])->delete();

        return match ($params['type']) {
            'from_recipe' => $this->storeFromRecipe($request),
            'from_fast_food_store' => $this->storeFromFastFoodStore($request),
            'from_ingredients' => $this->storeFromIngredients($request),
            'quick_entry' => $this->storeFromQuickEntry($request),
            default => ResponseUtils::generateErrorResponse('Invalid type'),
        };


    }

    public function show(Request $request, CalendarEntry $calendarEntry) {

        $user = $request->user();

        if($calendarEntry->user_id !== $user->id) {
            return ResponseUtils::generateErrorResponse('Unauthorized', 401);
        }

        return ResponseUtils::generateSuccessResponse($calendarEntry);
    }

    public function update(Request $request, CalendarEntry $calendarEntry) {

        $fields = $request->validate([
            'type' => 'in:from_recipe,from_fast_food_store,from_ingredients,quick_entry',
            'date' => 'date',
            'meal_order' => 'integer',
        ]);

        $user = $request->user();

        if($calendarEntry->user_id !== $user->id) {
            return ResponseUtils::generateErrorResponse('Unauthorized', 401);
        }

        $user->calendarEntries()->where('date', $fields['date'])->where('meal_order', $fields['meal_order'])->delete();


        return match ($fields['type']) {
            'from_recipe' => $this->updateFromRecipe($request, $calendarEntry),
            'from_fast_food_store' => $this->updateFromFastFoodStore($request, $calendarEntry),
            'from_ingredients' => $this->updateFromIngredients($request, $calendarEntry),
            'quick_entry' => $this->updateFromQuickEntry($request, $calendarEntry),
            default => ResponseUtils::generateErrorResponse('Invalid type'),
        };
    }

    public function destroy(Request $request ,CalendarEntry $calendarEntry) {
        $user = $request->user();

        if($calendarEntry->user_id !== $user->id) {
            return ResponseUtils::generateErrorResponse('Unauthorized', 401);
        }

        if($calendarEntry->recipe_id !== 0) {
            $recipe = Recipe::find($calendarEntry->recipe_id);
            if(isset($recipe) && $recipe->type === 'calendar_entry')
                $recipe->delete();
        }

        $calendarEntry->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }

    //--------------------------------------------------------------------------------
    // Helper functions
    //--------------------------------------------------------------------------------

    public function storeFromFastFoodStore(Request $request) {
        $fields = $request->validate([
            'fast_food_store_id' => 'required|integer',
            'date' => 'required|date',
            'meal_order' => 'integer|required',
        ]);

        $user = $request->user();
        $fastFoodStore = FastFoodStore::findOrFail($fields['fast_food_store_id']);

        $entry = $user->calendarEntries()->create([
            'fast_food_store_id' => $fastFoodStore->id,
            'entry_type' => 'from_fast_food_store',
            'calories' => 0,
            'date' => $fields['date'],
            'meal_order' => $fields['meal_order'],
            'recipe_id' => 0
        ]);

        return ResponseUtils::generateSuccessResponse($entry);
    }

    public function updateFromFastFoodStore(Request $request) {
        $fields = $request->validate([
            'fast_food_store_id' => 'required|integer',
            'date' => 'required|date',
            'meal_order' => 'integer|required',
        ]);

        $user = $request->user();
        $fastFoodStore = FastFoodStore::findOrFail($fields['fast_food_store_id']);

        $entry = $user->calendarEntries()->create([
            'fast_food_store_id' => $fastFoodStore->id,
            'entry_type' => 'from_fast_food_store',
            'calories' => 0,
            'date' => $fields['date'],
            'meal_order' => $fields['meal_order'],
            'recipe_id' => 0
        ]);

        return ResponseUtils::generateSuccessResponse($entry);
    }

    public function storeFromRecipe(Request $request) {
        $fields = $request->validate([
            'recipe_id' => 'required|integer',
            'date' => 'required|date',
            'meal_order' => 'integer|required',
        ]);

        $user = $request->user();
        $recipe = Recipe::findOrFail($fields['recipe_id']);

        $entry = $user->calendarEntries()->create([
            'recipe_id' => $recipe->id,
            'entry_type' => 'from_recipe',
            'calories' => $recipe->calories_per_serving,
            'date' => $fields['date'],
            'meal_order' => $fields['meal_order'],
        ]);

        return ResponseUtils::generateSuccessResponse($entry);
    }

    public function updateFromRecipe(Request $request, CalendarEntry $calendarEntry) {
        $request = $request->validate([
            'recipe_id' => 'integer',
            'date' => 'date',
            'meal_order' => 'integer',
        ]);

        $user = $request->user();

        $recipe = Recipe::findOrFail($request['recipe_id']);

        $calendarEntry->update([
            'recipe_id' => $recipe->id,
            'calories' => $recipe->calories_per_serving,
            'date' => $request['date'],
            'meal_order' => $request['meal_order'],
        ]);

        return ResponseUtils::generateSuccessResponse($calendarEntry);
    }

    public function storeFromIngredients(Request $request) {
        $fields = $request->validate([
            'date' => 'required|date',
            'meal_order' => 'integer|required',
            'ingredients' => 'array',
            'ingredients.*.ingredient_id' => 'required|exists:products,id',
            'ingredients.*.unit_id' => 'required|exists:global_units,id',
            'ingredients.*.amount' => 'numeric|required',
        ]);

        $user = $request->user();

        $entry = $user->calendarEntries()->create([
            'entry_type' => 'from_ingredients',
            'calories' => 0,
            'date' => $fields['date'],
            'meal_order' => $fields['meal_order'],
            'recipe_id' => 0,
        ]);

        if(isset($fields['ingredients'])) {
            foreach ($fields['ingredients'] as $ing) {
                $entry->calendarEntryIngredients()->create($ing);
            }
        }

        return ResponseUtils::generateSuccessResponse($entry);
    }

    public function updateFromIngredients(Request $request, CalendarEntry $calendarEntry) {
        $fields = $request->validate([
            'date' => 'date',
            'meal_order' => 'integer',
            'ingredients' => 'array',
            'ingredients.*.ingredient_id' => 'required|exists:products,id',
            'ingredients.*.unit_id' => 'required|exists:global_units,id',
            'ingredients.*.amount' => 'numeric|required',
        ]);

        $calendarEntry->update([
            'entry_type' => 'from_ingredients',
            'date' => $fields['date'] ?? $calendarEntry->date,
            'meal_order' => $fields['meal_order'] ?? $calendarEntry->meal_order,
            'recipe_id' => 0,
            'fast_food_store_id' => null,
        ]);

        if(isset($fields['ingredients'])) {
            $calendarEntry->calendarEntryIngredients()->delete();
            foreach ($fields['ingredients'] as $ing) {
                $calendarEntry->calendarEntryIngredients()->create($ing);
            }
        }

        return ResponseUtils::generateSuccessResponse($calendarEntry);
    }

    public function storeFromQuickEntry(Request $request) {
        $fields = $request->validate([
            'date' => 'required|date',
            'meal_order' => 'required|integer',
            'name' => 'required|string',
            'calories' => 'required|numeric',
        ]);

        $user = $request->user();

        $entry = $user->calendarEntries()->create([
            'entry_type' => 'quick_entry',
            'calories' => $fields['calories'],
            'date' => $fields['date'],
            'meal_order' => $fields['meal_order'],
            'recipe_id' => 0,
            'fast_food_store_id' => null,
        ]);

        $entry->calendarEntryQuickEntries()->create([
            'name' => $fields['name'],
            'calories' => $fields['calories'],
        ]);

        return ResponseUtils::generateSuccessResponse($entry);
    }

    public function updateFromQuickEntry(Request $request, CalendarEntry $calendarEntry) {
        $fields = $request->validate([
            'date' => 'date',
            'meal_order' => 'integer',
            'name' => 'string',
            'calories' => 'numeric',
        ]);

        $calendarEntry->update([
            'entry_type' => 'quick_entry',
            'date' => $fields['date'] ?? $calendarEntry->date,
            'meal_order' => $fields['meal_order'] ?? $calendarEntry->meal_order,
            'calories' => $fields['calories'] ?? $calendarEntry->calories,
            'recipe_id' => 0,
            'fast_food_store_id' => null,
        ]);

        $quick = $calendarEntry->calendarEntryQuickEntries()->first();
        if($quick){
            $quick->update([
                'name' => $fields['name'] ?? $quick->name,
                'calories' => $fields['calories'] ?? $quick->calories,
            ]);
        }

        return ResponseUtils::generateSuccessResponse($calendarEntry);
    }


}
