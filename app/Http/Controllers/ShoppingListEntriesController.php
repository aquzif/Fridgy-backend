<?php

namespace App\Http\Controllers;

use App\Models\GlobalUnit;
use App\Models\ShoppingList;
use App\Models\ShoppingListEntry;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class ShoppingListEntriesController extends Controller {


    public function __construct() {
        $this->authorizeResource(ShoppingListEntry::class, ['shoppingListEntry','shoppingList']);
    }

    public function index(ShoppingList $shoppingList,Request $request) {
        return ResponseUtils::generateSuccessResponse($shoppingList->entries);
    }

    public function store(ShoppingList $shoppingList, Request $request) {

        $fields = $request->validate([
            'type' => 'string|required|in:raw,product,raw_product',
        ]);

        $fields = match($fields['type']){
            'raw' => self::vaidateRawCreate($shoppingList,$request),
            'raw_product' => self::validateRawProductCreate($shoppingList,$request),
            default => 'type not supported'
        };

        if($fields === 'type not supported')
            return ResponseUtils::generateErrorResponse('type not supported',400);

//        if($fields['category_id'] === 0)
//            $fields['category_id'] = null;
        if(!$fields['category_id'])
            $fields['category_id'] = null;

        $shoppingListEntry = ShoppingListEntry::create($fields);
        $shoppingListEntry = ShoppingListEntry::find($shoppingListEntry['id']);

        return ResponseUtils::generateSuccessResponse($shoppingListEntry,'OK',201);

    }




    public function show(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        return ResponseUtils::generateSuccessResponse($shoppingListEntry);
    }

    public function update(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {


        $fields = match($shoppingListEntry->type){
            'raw' => self::validateRawUpdate($shoppingList,$request,$shoppingListEntry),
            'raw_product' => self::validateRawProductUpdate($shoppingList,$request,$shoppingListEntry),
            default => 'type not supported'
        };

        if($fields === 'type not supported')
            return ResponseUtils::generateErrorResponse('type not supported',400);

        if(!$fields['category_id'])
            $fields['category_id'] = null;

        $shoppingListEntry->update($fields);
        $shoppingListEntry = $shoppingListEntry->find($shoppingListEntry['id']);
        return ResponseUtils::generateSuccessResponse($shoppingListEntry);
    }

    public function check(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {

        $fields = $request->validate([
            'checked' => 'boolean|required',
        ]);

        $shoppingListEntry->update($fields);
        $shoppingListEntry = $shoppingListEntry->find($shoppingListEntry['id']);
        return ResponseUtils::generateSuccessResponse($shoppingListEntry);
    }

    public function destroy(ShoppingList $shoppingList, ShoppingListEntry $shoppingListEntry, Request $request) {
        $shoppingListEntry->delete();
        return ResponseUtils::generateSuccessResponse('Deleted successfully');
    }


    //-------------------------------//
    //--------CUSTOM METHODS---------//
    //-------------------------------//


    public function validateRawProductCreate(ShoppingList $shoppingList, Request $request) {
        $fields = $request->validate([
            'product_name' => 'string|required',
            'unit_id' => 'integer|required|exists:global_units,id',
            'amount' => 'integer|required',
            'checked' => 'boolean',
            'type' => 'string',
            'category_id' => 'numeric|nullable',
        ]);

        $unit = GlobalUnit::find($fields['unit_id']);
        $fields['unit_name'] = $unit->name;
        $fields['shopping_list_id'] = $shoppingList->id;
        return $fields;

    }

    public function vaidateRawCreate(ShoppingList $shoppingList, Request $request) {
        $fields = $request->validate([
            'product_name' => 'string|required',
            'checked' => 'boolean',
            'type' => 'string',
             'category_id' => 'numeric|nullable',
        ]);

        $fields['shopping_list_id'] = $shoppingList->id;
        return $fields;

    }

    public function validateRawProductUpdate(ShoppingList $shoppingList, Request $request,ShoppingListEntry $shoppingListEntry) {
        $fields = $request->validate([
            'product_name' => 'string',
            'unit_id' => 'integer|exists:global_units,id',
            'amount' => 'integer',
            'checked' => 'boolean',
            'type' => 'string',
            'category_id' => 'numeric|nullable',
        ]);
        $unit = GlobalUnit::find($shoppingListEntry->unit_id);

        if(isset($fields['unit_id']) )
            $unit = GlobalUnit::find($fields['unit_id']);

        $fields['unit_name'] = $unit->name;
        $fields['shopping_list_id'] = $shoppingList->id;
        return $fields;

    }

    public function validateRawUpdate(ShoppingList $shoppingList, Request $request,ShoppingListEntry $shoppingListEntry) {
        $fields = $request->validate([
            'product_name' => 'string',
            'checked' => 'boolean',
            'type' => 'string',
            'category_id' => 'numeric|nullable',
        ]);

        $fields['shopping_list_id'] = $shoppingList->id;
        return $fields;

    }

}
