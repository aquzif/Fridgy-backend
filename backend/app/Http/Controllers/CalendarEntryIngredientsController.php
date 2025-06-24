<?php

namespace App\Http\Controllers;

use App\Models\CalendarEntry;
use App\Models\CalendarEntryIngredient;
use App\Models\GlobalUnit;
use App\Models\Product;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class CalendarEntryIngredientsController extends Controller
{
    public function index(CalendarEntry $calendarEntry, Request $request)
    {
        return $calendarEntry->calendarEntryIngredients;
    }

    public function store(CalendarEntry $calendarEntry, Request $request)
    {
        $fields = $request->validate([
            'ingredient_id' => 'required|exists:products,id',
            'unit_id' => 'required|exists:global_units,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $calendarEntry->calendarEntryIngredients()->create($fields);
        $entryIngredient = $calendarEntry->calendarEntryIngredients()->latest()->first();

        return ResponseUtils::generateSuccessResponse($entryIngredient, 'OK', 201);
    }

    public function show(CalendarEntry $calendarEntry, CalendarEntryIngredient $calendarEntryIngredient, Request $request)
    {
        return ResponseUtils::generateSuccessResponse($calendarEntryIngredient);
    }

    public function update(CalendarEntry $calendarEntry, CalendarEntryIngredient $calendarEntryIngredient, Request $request)
    {
        $fields = $request->validate([
            'ingredient_id' => 'exists:products,id',
            'unit_id' => 'exists:global_units,id',
            'amount' => 'numeric|min:0',
        ]);

        $calendarEntryIngredient->update($fields);

        return ResponseUtils::generateSuccessResponse($calendarEntryIngredient);
    }

    public function destroy(CalendarEntry $calendarEntry, CalendarEntryIngredient $calendarEntryIngredient, Request $request)
    {
        $calendarEntryIngredient->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
