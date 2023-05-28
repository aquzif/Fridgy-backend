<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListsController extends Controller {


    public function index(Request $request) {
        return ShoppingList::where('user_id', $request->user()->id)->get();
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
        ]);

         return ShoppingList::create([
            'name' => $fields['name'],
            'user_id' => $request->user()->id,
        ]);

    }

    public function show(ShoppingList $shoppingList) {
        return $shoppingList;
    }

    public function update(Request $request, ShoppingList $shoppingList) {

        $fields = $request->validate([
            'name' => 'string|nullable',
        ]);

        $shoppingList->update($fields);

        return $shoppingList;

    }

    public function destroy(ShoppingList $shoppingList) {
        $shoppingList->getEntries()->delete();
        $shoppingList->delete();
        return response()->json(['message' => 'Shopping list deleted']);
    }
}
