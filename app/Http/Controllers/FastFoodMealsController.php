<?php

namespace App\Http\Controllers;

use App\Models\FastFoodMeal;
use App\Models\FastFoodStore;
use App\Utils\ResponseUtils;
use App\Utils\StorageUtils;
use Illuminate\Http\Request;

class FastFoodMealsController extends Controller
{
    public function index(FastFoodStore $fastFoodStore) {
        return ResponseUtils::generateSuccessResponse($fastFoodStore->meals()->get());
    }


    public function store(FastFoodStore $fastFoodStore,Request $request) {
        $fields = $request->validate([
            'name' => 'string|required',
            'weight_in_grams' => 'integer',
            'calories_per_100g' => 'numeric',
            'category'  => 'string|required',
            'image' => 'image',
        ]);

        if(isset($fields['image'])) {
            $fields['image'] = StorageUtils::storeImage($fields['image']);
        }

        $fastFoodMeal = $fastFoodStore->meals()->create($fields);
        return ResponseUtils::generateSuccessResponse($fastFoodMeal);

    }

    public function show(FastFoodStore $fastFoodStore, FastFoodMeal $fastFoodMeal) {
        return ResponseUtils::generateSuccessResponse($fastFoodMeal);
    }


    public function update(FastFoodStore $fastFoodStore, Request $request, FastFoodMeal $fastFoodMeal) {
        $fields = $request->validate([
            'name' => 'string',
            'weight_in_grams' => 'integer',
            'calories_per_100g' => 'numeric',
            'category'  => 'string',
            'image' => 'image',
        ]);

        if(isset($fields['image'])) {
            $fields['image'] = StorageUtils::storeImage($fields['image']);
            $fastFoodMeal->deleteImage();
        }

        $fastFoodMeal->update($fields);
        return ResponseUtils::generateSuccessResponse($fastFoodMeal);

    }

    public function destroy(FastFoodStore $fastFoodStore, FastFoodMeal $fastFoodMeal) {
        $fastFoodMeal->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
