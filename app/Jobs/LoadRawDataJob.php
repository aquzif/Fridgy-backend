<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadRawDataJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {

    }

    public function handle(): void {
        $file = storage_path('data/raw.txt');

        $resultData= [];
        $newData = [];

        $handle = fopen($file, 'r');
        $i = 0;
        while (($line = fgets($handle)) !== false) {
            if($i > 100) break;

            if($i % 5 === 0) {
                if(!empty($newData)){
                    $resultData[] = $newData;
                }
                $newData = [];
                $newData['name'] = trim($line);
            }
            if(($i-1) % 5 === 0) {
                $newData['kcal'] = floatval(trim($line));
            }
            if(($i-2) % 5 === 0) {
                $newData['carbs'] = floatval(trim($line));
            }
            if(($i-3) % 5 === 0) {
                $newData['protein'] = floatval(trim($line));
            }
            if(($i-4) % 5 === 0) {
                $newData['fat'] = floatval(trim($line));
            }
            $i++;
        }
        fclose($handle);

        foreach ($resultData as $resultDatum) {
            echo "Loading: " . $resultDatum['name'] . "\n";

            foreach (Product::where('name', $resultDatum['name'])->get() as $item) {
                $item->delete();
                echo "Found duplikate, deleting: " . $item->name . "\n";
            }


            Product::create([
                'name' => $resultDatum['name'],
                'nutrition_energy_kcal' => $resultDatum['kcal'],
                'nutrition_carbs' => $resultDatum['carbs'],
                'nutrition_fat' => $resultDatum['fat'],
                'nutrition_protein' => $resultDatum['protein'],
            ]);

        }

    }
}
