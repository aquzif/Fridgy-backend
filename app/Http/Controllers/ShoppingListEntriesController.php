<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Models\ShoppingListEntry;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class ShoppingListEntriesController extends Controller {


    public function index(ShoppingList $shoppingList,Request $request) {
        return ResponseUtils::generateSuccessResponse($shoppingList->entries);
    }

    public function store(ShoppingList $shoppingList, Request $request) {

        $fields = $request->validate([
            'product_name' => 'string|required',
            'unit_name' => 'string|required',
            'amount' => 'integer|required',
            'checked' => 'boolean',
        ]);


        $fields['shopping_list_id'] = $shoppingList->id;

        return response(ResponseUtils::generateSuccessResponse(ShoppingListEntry::create($fields),'OK',201),201);

    }

    public function show(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        return ResponseUtils::generateSuccessResponse($shoppingListEntry);
    }

    public function update(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request ) {
        $fields = $request->validate([
            'product_name' => 'string',
            'unit_name' => 'string',
            'amount' => 'integer',
            'checked' => 'boolean',
        ]);

        $shoppingListEntry->update($fields);
        return ResponseUtils::generateSuccessResponse($shoppingListEntry);
    }

    public function destroy(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        $shoppingListEntry->delete();
        return ResponseUtils::generateSuccessResponse('Deleted successfully');

    }
}
