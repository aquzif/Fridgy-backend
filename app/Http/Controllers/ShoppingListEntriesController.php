<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Models\ShoppingListEntry;
use Illuminate\Http\Request;

class ShoppingListEntriesController extends Controller {


    public function index(ShoppingList $shoppingList,Request $request) {
        return $shoppingList->getEntries()->get();
    }

    public function store(ShoppingList $shoppingList, Request $request) {

        $fields = $request->validate([
            'product_name' => 'string|required',
            'unit_name' => 'string|required',
            'amount' => 'integer|required',
        ]);


        $fields['shopping_list_id'] = $shoppingList->id;

        return ShoppingListEntry::create($fields);

    }

    public function show(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        return $shoppingListEntry;
    }

    public function update(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request ) {
        $fields = $request->validate([
            'product_name' => 'string',
            'unit_name' => 'string',
            'amount' => 'integer',
        ]);

        $shoppingListEntry->update($fields);
        return $shoppingListEntry;
    }

    public function destroy(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        $shoppingListEntry->delete();
        return response()->json(['message' => 'Shopping list entry deleted']);

    }
}
