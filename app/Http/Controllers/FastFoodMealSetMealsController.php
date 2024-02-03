<?php

namespace App\Http\Controllers;

use App\Models\FastFoodMeal;
use App\Models\FastFoodMealInSet;
use App\Models\FastFoodMealSet;
use App\Models\FastFoodStore;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class FastFoodMealSetMealsController extends Controller
{
    public function index(FastFoodStore $fastFoodStore,FastFoodMealSet $fastFoodMealSet) {
        return ResponseUtils::generateSuccessResponse($fastFoodMealSet->meals()->get());
    }


    public function store(FastFoodStore $fastFoodStore,FastFoodMealSet $fastFoodMealSet,Request $request) {
        $fields = $request->validate([
            'quantity' => 'integer|required',
            'meal_id' => 'integer|required'
        ]);

        $fastFoodMealSet->meals()->create($fields);

    }

    public function show(FastFoodStore $fastFoodStore,FastFoodMealSet $fastFoodMealSet,FastFoodMealInSet $fastFoodMealInSet) {
        return ResponseUtils::generateSuccessResponse($fastFoodMealInSet);
    }


    public function update(Request $request, FastFoodStore $fastFoodStore,FastFoodMealSet $fastFoodMealSet,FastFoodMealInSet $fastFoodMealInSet){
        $fields = $request->validate([
            'quantity' => 'integer|required'
        ]);

        $fastFoodMealInSet->update($fields);

    }

    public function destroy(FastFoodStore $fastFoodStore,FastFoodMealSet $fastFoodMealSet,FastFoodMealInSet $fastFoodMealInSet) {
        $fastFoodMealInSet->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
