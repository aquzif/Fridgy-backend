<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//if url is /storage, redirect to /storage/app/public, and try providing the file
Route::get('/storage/{path}', function ($path) {
    return redirect('/storage/app/public/'.$path);
})->where('path', '.*');

Route::fallback(function () {
    return view('react');
});
