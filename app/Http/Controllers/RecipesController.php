<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RecipesController extends Controller {

    public function index(Request $request) {

        $fields = $request->validate([
            'perPage' => 'numeric'
        ]);



        if(!empty($fields['perPage']))
            return ResponseUtils::generateSuccessResponse(Recipe::paginate($fields['perPage']));
        else
            return ResponseUtils::generateSuccessResponse(Recipe::paginate(10000));
    }

    public function search(Request $request) {
        return Recipe::search($request->input('query'))->get();
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
            $uplaodedFile = $fields['image'];
            $newFileName = Str::random(40) . '.' . $uplaodedFile->getClientOriginalExtension();
            Storage::put('/public/images/' . $newFileName, $uplaodedFile->getContent());
            $recipe->deleteImage();
            $fields['image'] = '/storage/images/' . $newFileName;
        }
        $recipe->update($fields);


    }

    public function destroy(Recipe $recipe) {
        $recipe->delete();
    }

}
