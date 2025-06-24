<?php

namespace App\Http\Controllers;

use App\Models\FastFoodMealSet;
use App\Models\FastFoodStore;
use App\Utils\ResponseUtils;
use App\Utils\StorageUtils;
use Illuminate\Http\Request;

class FastFoodMealSetsController extends Controller
{
    public function index(FastFoodStore $fastFoodStore){
        return ResponseUtils::generateSuccessResponse($fastFoodStore->sets()->get());
    }


    public function store(FastFoodStore $fastFoodStore,Request $request) {
        $fields = $request->validate([
            'name' => 'string|required'
        ]);

        $fastFoodMealSet = $fastFoodStore->sets()->create($fields);

        return ResponseUtils::generateSuccessResponse($fastFoodMealSet);
    }

    public function show(FastFoodStore $fastFoodStore,FastFoodMealSet $fastFoodMealSet) {
        return ResponseUtils::generateSuccessResponse($fastFoodMealSet);
    }



    public function update(Request $request, FastFoodStore $fastFoodStore,FastFoodMealSet $fastFoodMealSet) {
        $fields = $request->validate([
            'name' => 'string',
            'image' => 'image',
        ]);

        if(isset($fields['image'])) {
            $fields['image'] = StorageUtils::storeImage($fields['image']);
            $fastFoodMealSet->deleteImage();
        }

        $fastFoodMealSet->update($fields);
        return ResponseUtils::generateSuccessResponse($fastFoodMealSet);
    }

    public function destroy(FastFoodStore $fastFoodStore, FastFoodMealSet $fastFoodMealSet) {
        $fastFoodMealSet->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
