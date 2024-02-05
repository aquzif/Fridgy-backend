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

    public function insertCalendarEntries(ShoppingList $shoppingList, Request $request) {
        $fields = $request->validate([
            'entries_ids' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date'
        ]);

        $fields['entries_ids'] = explode(',', $fields['entries_ids']);
        
        $calendarEntries =
            $request->user()->calendarEntries()
                ->whereBetween('date', [$fields['date_from'], $fields['date_to']])
                ->whereIn('id', $fields['entries_ids'])
                ->orderBy('date')
                ->get();

        if($calendarEntries->isEmpty()) {
            return ResponseUtils::generateErrorResponse('No calendar entries found for the given date range', 404);
        }

        $shoppingListEntriesToInsert = [];


        foreach ($calendarEntries as $calendarEntry) {
            $ingredients = $calendarEntry->recipe->ingredients()->get();

            foreach ($ingredients as $ingredient) {

                $product = $ingredient->product()->first();
                $unit = $ingredient->unit()->first();

                $found = false;

                foreach ($shoppingListEntriesToInsert as &$toInsert) {

                    if($toInsert['product_id'] == $product->id) {
                        $found = true;
                        if($toInsert['product_unit_id'] == $unit->id) {
                            $toInsert['amount'] += $ingredient->amount_in_unit;
                        } else {
                            $factor =  $unit->grams_per_unit /$toInsert['grams_per_unit'];
                            $toInsert['amount'] += $ingredient->amount_in_unit * $factor;
                        }
                    }

                }

                if(!$found) {
                    $shoppingListEntriesToInsert[] = [
                        'product_id' => $product->id,
                        'product_unit_id' => $unit->id,
                        'product_name' => $product->name,
                        'unit_name' => $unit->name,
                        'checked' => false,
                        'type' => 'product',
                        'amount' => $ingredient->amount_in_unit,
                        'grams_per_unit' => $unit->grams_per_unit,
                        'shopping_list_id' => $shoppingList->id,
                        'category_id' => $product->category_id,
                    ];
                }



            }


        }


        $shoppingList->entries()->delete();

        foreach ($shoppingListEntriesToInsert as &$item) {
            $item['amount']  = ceil($item['amount']);

            $shoppingList->entries()->create($item);

        }

        return ResponseUtils::generateSuccessResponse($shoppingList->entries()->get());




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
