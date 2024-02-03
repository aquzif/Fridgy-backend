<?php

namespace App\Http\Controllers;

use App\Models\CalendarEntry;
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
            'type' => 'required|in:from_recipe',
            'meal_order' => 'integer|required',
            'date' => 'required|date',
        ]);

        $user = $request->user();

        $user->calendarEntries()->where('date', $request['date'])->where('meal_order', $request['meal_order'])->delete();

        return match ($params['type']) {
            'from_recipe' => $this->storeFromRecipe($request),
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
            'type' => 'in:from_recipe',
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
            default => ResponseUtils::generateErrorResponse('Invalid type'),
        };
    }

    public function destroy(Request $request ,CalendarEntry $calendarEntry) {
        $user = $request->user();

        if($calendarEntry->user_id !== $user->id) {
            return ResponseUtils::generateErrorResponse('Unauthorized', 401);
        }

        $calendarEntry->delete();
        return RequestUtils::generateSuccessResponse('OK');
    }

    //--------------------------------------------------------------------------------
    // Helper functions
    //--------------------------------------------------------------------------------

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


}
