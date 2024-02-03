<?php

namespace App\Http\Controllers;

use App\Models\FastFoodStore;
use App\Utils\ResponseUtils;
use App\Utils\StorageUtils;
use Illuminate\Http\Request;

class FastFoodStoresController extends Controller
{
    public function index() {
        return ResponseUtils::generateSuccessResponse(FastFoodStore::all());
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'string|required',
        ]);

        $fastFoodStore = FastFoodStore::create([
            'name' => $fields['name'],
        ]);

        return ResponseUtils::generateSuccessResponse($fastFoodStore);
    }

    public function show(FastFoodStore $fastFoodStore) {
        return ResponseUtils::generateSuccessResponse($fastFoodStore);
    }


    public function update(Request $request, FastFoodStore $fastFoodStore) {
        $fields = $request->validate([
            'name' => 'string',
            'image' => 'image',
        ]);

        if(isset($fields['image'])) {
            $fields['image'] = StorageUtils::storeImage($fields['image']);
            $fastFoodStore->deleteImage();
        }

        $fastFoodStore->update($fields);
        return ResponseUtils::generateSuccessResponse($fastFoodStore);
    }

    public function destroy(FastFoodStore $fastFoodStore) {
        $fastFoodStore->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
