<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class ShoppingListsController extends Controller {

    public function __construct() {
        $this->authorizeResource(ShoppingList::class, 'shoppingList');
    }


    public function index(Request $request) {
        return ResponseUtils::generateSuccessResponse(ShoppingList::where('user_id', $request->user()->id)->get());
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'type' => 'string|in:default,grouped',
        ]);

         $newShoppingList = ShoppingList::create([
             ...$fields,
             'user_id' => $request->user()->id,
         ]);
        $newShoppingList = $newShoppingList->where('id',$newShoppingList['id'])->first();


         return ResponseUtils::generateSuccessResponse($newShoppingList,'OK',201);

    }

    public function show(ShoppingList $shoppingList) {
        return ResponseUtils::generateSuccessResponse($shoppingList);
    }

    public function update(Request $request, ShoppingList $shoppingList) {

        $fields = $request->validate([
            'name' => 'string',
            'type' => 'string|in:default,grouped',
        ]);

        $shoppingList->update($fields);

        return ResponseUtils::generateSuccessResponse($shoppingList);

    }

    public function destroy(ShoppingList $shoppingList) {
        $shoppingList->delete();
        return ResponseUtils::generateSuccessResponse();
    }
}
