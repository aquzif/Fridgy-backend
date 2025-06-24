<?php

namespace App\Http\Controllers;

use App\Models\RecipeTag;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class RecipeTagsController extends Controller
{
    public function index(){
        return ResponseUtils::generateSuccessResponse(RecipeTag::all());
    }


    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
        ]);

        $newObj = RecipeTag::create($fields);
        $newObj = $newObj->where('id',$newObj['id'])->first();

        return ResponseUtils::generateSuccessResponse($newObj,'Created',201);
    }

    public function show(RecipeTag $recipeTag){
        return ResponseUtils::generateSuccessResponse($recipeTag);
    }


    public function update(Request $request, RecipeTag $recipeTag) {
        $fields = $request->validate([
            'name' => 'string',
            'color' => 'string',
        ]);

        $recipeTag->update($fields);
        return ResponseUtils::generateSuccessResponse($recipeTag);
    }


    public function destroy(RecipeTag $recipeTag) {
        $recipeTag->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
