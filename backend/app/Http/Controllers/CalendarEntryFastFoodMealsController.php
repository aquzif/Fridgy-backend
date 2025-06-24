<?php

namespace App\Http\Controllers;

use App\Models\CalendarEntry;
use App\Models\CalendarEntryFastFoodMeal;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class CalendarEntryFastFoodMealsController extends Controller
{
    public function index(CalendarEntry $calendarEntry, Request $request) {
        return $calendarEntry->calendarEntryFastFoodMeals;
    }


    public function store(CalendarEntry $calendarEntry, Request $request) {
        $fields = $request->validate([
            'fast_food_meal_id' => 'required|exists:fast_food_meals,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $calendarEntry->calendarEntryFastFoodMeals()->create($fields);
        $calendarMeal = $calendarEntry->calendarEntryFastFoodMeals()->latest()->first();

        return ResponseUtils::generateSuccessResponse($calendarMeal);

    }

    public function show(CalendarEntry $calendarEntry, CalendarEntryFastFoodMeal $calendarEntryFastFoodMeal, Request $request) {
        return ResponseUtils::generateSuccessResponse($calendarEntryFastFoodMeal);
    }


    public function update(CalendarEntry $calendarEntry, CalendarEntryFastFoodMeal $calendarEntryFastFoodMeal, Request $request) {
        $fields = $request->validate([
            'fast_food_meal_id' => 'exists:fast_food_meals,id',
            'quantity' => 'integer|min:1',
        ]);

        $calendarEntryFastFoodMeal->update($fields);

        return ResponseUtils::generateSuccessResponse($calendarEntryFastFoodMeal);
    }

    public function destroy(CalendarEntry $calendarEntry, CalendarEntryFastFoodMeal $calendarEntryFastFoodMeal, Request $request) {
        $calendarEntryFastFoodMeal->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
