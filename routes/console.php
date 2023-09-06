<?php

use App\Jobs\LoadRawDataJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use MongoDB\Driver\ServerApi;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('load:raw', function () {
    $this->comment('Loading raw data...');
    dispatch(new LoadRawDataJob());
})->purpose('Load raw data from the API');

Artisan::command('load:off', function () {

    $apiVersion = new ServerApi(ServerApi::V1);
    $uri = env('MONGODB_CONNECTION','mongodb://localhost:27017');
    $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

    try {
        // Send a ping to confirm a successful connection
        $collection = $client->off->produkty;
        echo "Connected successfully to server \n";
        echo "Fetching data from database\n";
        $result = $collection->find([
            'product_name_pl' => [
                '$exists' => true,
                '$ne' => ""
            ]
        ],[
            'projection' => [
                'product_name_pl' => 1,
                'brands' => 1,
                'quantity' => 1,
                'nutriments' => [
                    "sugars_100g" => 1,
                    "energy_100g" => 1,
                    "energy-kcal_100g"=> 1,
                    "fat_100g" => 1,
                    "proteins_100g" => 1,
                    "salt_100g" => 1,
                    "carbohydrates_100g" => 1
                ]
            ]
        ]);
        echo "parsing data to array...\n";
        //get result size
        $resultSize = $result->toArray();

        foreach ($resultSize as $item) {
            dd($item);
        }

    } catch (Exception $e) {
        printf($e->getMessage());
    }


});
