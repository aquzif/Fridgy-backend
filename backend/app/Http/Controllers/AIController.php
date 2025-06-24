<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Utils\OpenAIUtils;
use Illuminate\Http\Request;
use OpenAI;

class AIController extends Controller
{



    public function nutrition(Request $request) {

        $params = $request->validate([
            'product' => 'string',
            'meal' => 'string'
        ]);


        if (isset($params['product'])) {
            return OpenAIUtils::prompt('search for information about nutrition
            of "'.$params['product'].'" in 100 grams. Find informations about lowest and highest, then calculate average.
        You Will respond in raw JSON, without any nessasary markdown, as below:
        {
            "status": "FOUND",
            "name": "Name of the product, in detected language",
            "energy_kcal": "100",
            "energy_kj": "100",
            "protein": "10",
            "salt": "10",
            "fat": "10",
            "carbs": "10",
            "fiber": "10",
            "sugar": "10",
        }.
        If you don\'t have this information, please respond with {"status": "NOT_FOUND"}.');
        }else{
            return OpenAIUtils::prompt('search for information about average nutrition
            of "'.$params['meal'].'" meal.
        You Will respond in raw JSON, without any nessasary markdown, as below:
        {
            "status": "FOUND",
            "name": "Name of the meal, in detected language",
            "weight": "0",
            "kcal_min": "0",
            "kcal_max": "0",
            "protein": "10",
            "fat": "10",
            "carbs": "10",
            "fiber": "10",
            "sugar": "10",
        }.
        If you don\'t have this information, please respond with {"status": "NOT_FOUND"}.');
        }



    }

    public function shoppingListPrices(ShoppingList $shoppingList) {

        $shoppingListEntries = $shoppingList->entries()->get()->toArray();

        $jsonToPrompt = [];



        foreach ($shoppingListEntries as $shoppingListEntry) {
            $jsonToPrompt[] = [
                'id' => $shoppingListEntry['id'],
                'name' => $shoppingListEntry['product_name'],
                'unit' => 'kg',//$shoppingListEntry['unit_name'],
                'quantity' => $shoppingListEntry['amount'],
            ];
        }

        //dd($jsonToPrompt);

        return OpenAIUtils::prompt('Wyszukaj informację o średniej cenie podanych produktów.
            Produkty otrzymasz w formacie JSON, bez żadnego formatowania, jak poniżej:
            [
                {
                    "id": "1",
                    "name": "Name of the product, in detected language",
                    "unit": "kg",
                    "quantity": "1",
                },
                {
                    "id": "2",
                    "name": "Name of the product, in detected language",
                    "unit": "kg",
                    "quantity": "1",
                }
            ].
        Odpowiesz w formacie JSON, bez żadnego formatowania, jak poniżej:
        [{
            "status": "FOUND",
            "id": "1",
            "name": "Nazwa produktu",
            "price_in_pln": "10",
        }].

        Jeżeli nie znajdziesz ceny danego produktu, zamiast tego odpowiedz: {"status": "NOT_FOUND"}.
        Poniżej znajduje się lista produktów do wyszukania cen:
        '.json_encode($jsonToPrompt));

    }

}
