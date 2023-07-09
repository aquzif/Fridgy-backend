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
            'type' => 'string|required|in:raw,product,raw_product',
        ]);

        $fields = match($fields['type']){
            'raw' => self::vaidateRaw($shoppingList,$request),
            'raw_product' => self::validateRawProduct($shoppingList,$request),
            default => 'type not supported'
        };

        if($fields === 'type not supported')
            return ResponseUtils::generateErrorResponse('type not supported',400);

        return response(ResponseUtils::generateSuccessResponse(ShoppingListEntry::create($fields),'OK',201),201);

    }

    public function vaidateRaw(ShoppingList $shoppingList, Request $request) {
        $fields = $request->validate([
            'product_name' => 'string|required',
            'checked' => 'boolean',
            'type' => 'string'
        ]);


        $fields['shopping_list_id'] = $shoppingList->id;
        return $fields;

    }

    public function validateRawProduct(ShoppingList $shoppingList, Request $request) {
        $fields = $request->validate([
            'product_name' => 'string|required',
            'unit_id' => 'integer|required|exists:global_units,id',
            'amount' => 'integer|required',
            'checked' => 'boolean',
            'type' => 'string'
        ]);

        $fields['shopping_list_id'] = $shoppingList->id;
        return $fields;

    }

    public function show(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        return ResponseUtils::generateSuccessResponse($shoppingListEntry);
    }

    public function update(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request ) {
        $fields = $request->validate([
            'type' => 'string|required|in:raw,product,raw_product',
        ]);

        $fields = match($fields['type']){
            'raw' => self::vaidateRaw($shoppingList,$request),
            'raw_product' => self::validateRawProduct($shoppingList,$request),
            default => 'type not supported'
        };

        if($fields === 'type not supported')
            return ResponseUtils::generateErrorResponse('type not supported',400);


        $shoppingListEntry->update($fields);
        return ResponseUtils::generateSuccessResponse($shoppingListEntry);
    }

    public function destroy(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        $shoppingListEntry->delete();
        return ResponseUtils::generateSuccessResponse('Deleted successfully');

    }
}
