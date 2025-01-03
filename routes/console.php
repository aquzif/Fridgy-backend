<?php

use App\Console\Commands\LoadCategoryImport;
use App\Console\Commands\PrepareCategoryImport;
use App\Jobs\LoadRawDataJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

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

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('load:raw', function () {
    $this->comment('Loading raw data...');
    dispatch(new LoadRawDataJob());
})->purpose('Load raw data from the API');
Artisan::command('load:categoryImport', function () {
    LoadCategoryImport::run();
})->purpose('Load category import');

Artisan::command('prepare:categoryImport', function () {
    PrepareCategoryImport::run();
})->purpose('Prepare category import');
