<?php

namespace App\Http\Controllers;

use App\Models\GroceryProduct;
use App\Models\GroceryProductUnit;
use App\Models\ShoppingList;
use App\Models\ShoppingListEntry;
use Illuminate\Http\Request;

class ShoppingListEntriesController extends Controller {


    public function index(ShoppingList $shoppingList,Request $request) {
        return $shoppingList->getEntries()->get();
    }

    public function store(ShoppingList $shoppingList, Request $request) {

        $fields = $request->validate([
            'grocery_product_id' => 'integer',
            'grocery_product_unit_id' => 'integer|required_unless:grocery_product_id,null',
            'product_name' => 'string|required_if:grocery_product_id,null',
            'unit_name' => 'string|required_if:grocery_product_id,null',
            'amount' => 'integer|required',
            'converter' => 'integer|required_if:grocery_product_id,null',
        ]);

        if(isset($fields['grocery_product_id'])) {
            $product = GroceryProduct::findOrFail($fields['grocery_product_id']);
            $unit = GroceryProductUnit::findOrFail($fields['grocery_product_unit_id']);

            $fields['product_name'] = $product->name;
            $fields['unit_name'] = $unit->name;
            $fields['converter'] = $unit->converter;
        }else{
            $fields['grocery_product_id'] = 0;
            $fields['grocery_product_unit_id'] = 0;
        }

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
            'converter' => 'integer',
        ]);

        if($shoppingListEntry->grocery_product_id !== 0){
            unset($fields['product_name']);
            unset($fields['unit_name']);
            unset($fields['converter']);
        }

        $shoppingListEntry->update($fields);
        return $shoppingListEntry;
    }

    public function destroy(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        $shoppingListEntry->delete();
        return response()->json(['message' => 'Shopping list entry deleted']);

    }
}
