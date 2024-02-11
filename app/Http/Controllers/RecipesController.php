<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Utils\ResponseUtils;
use App\Utils\StorageUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecipesController extends Controller {

    public function index(Request $request) {

        $fields = $request->validate([
            'selectedTags' => 'string|nullable',
            'needAllTags' => 'boolean|nullable',
        ]);

        if(!isset($fields['selectedTags']) || $fields['selectedTags'] == '[]')
            return ResponseUtils::generateSuccessResponse(
                Recipe::paginate(12)
            );

        $selectedTagsArray = json_decode($fields['selectedTags']);

        $tagsIDS = json_decode($fields['selectedTags']);

        $query = Recipe::query();
        foreach ($selectedTagsArray as $tag) {
            if(isset($fields['needAllTags']) && $fields['needAllTags'])
                $query->whereRaw("JSON_CONTAINS(tags, \"$tag\")");
            else
                $query->orWhereRaw("JSON_CONTAINS(tags, \"$tag\")");
        }


        return ResponseUtils::generateSuccessResponse(
            $query
                ->orderBy('id','desc')
                ->paginate(12)
        );
    }

    public function search(Request $request) {
        return ResponseUtils::generateSuccessResponse(
            Recipe::search($request->input('query'))
                ->orderBy('id','desc')
                ->paginate(12)
        );
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'string|required',
            'prepare_time' => 'numeric',
            'serving_amount' => 'numeric',
            'image' => 'string',
            'video_url' => 'string|nullable',
            'steps' => 'json',
        ]);

        if(!isset($fields['prepare_time']))
            $fields['prepare_time'] = 60;

        if(!isset($fields['serving_amount']))
            $fields['serving_amount'] = 1;

        $newObj = Recipe::create($fields);

        return ResponseUtils::generateSuccessResponse(
            $newObj->where('id',$newObj['id'])->first()
            ,'OK',201);
    }

    public function show(Recipe $recipe) {
        return ResponseUtils::generateSuccessResponse($recipe);
    }

    public function update(Request $request, Recipe $recipe) {
        $fields = $request->validate([
            'name' => 'string',
            'prepare_time' => 'numeric',
            'serving_amount' => 'numeric',
            'image' => 'file|image',
            'video_url' => 'string|nullable',
            'tags' => 'json',
            'steps' => 'json',
        ]);

        if(isset($fields['image'])) {
            $fields['image'] = StorageUtils::storeImage($fields['image']);
            $recipe->deleteImage();
        }
        $recipe->update($fields);


    }

    public function destroy(Recipe $recipe) {
        $recipe->delete();
    }

}
