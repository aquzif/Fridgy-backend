<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroceryProductsController;
use App\Http\Controllers\GroceryProductUnitsController;
use App\Http\Controllers\ShoppingListEntriesController;
use App\Http\Controllers\ShoppingListsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(fn() => [
    //Route::middleware('auth:sanctum')->post('/register','register');
    Route::post('/register','register'),
    Route::post('/login','login'),
    Route::middleware('auth:sanctum')->post('/logout','logout'),
]);


Route::middleware('auth:sanctum')->group(fn() => [

    //-----------------------------
    //Shopping list routes
    //-----------------------------
    Route::prefix('/shopping-list')->group(fn() => [
        Route::get('/',[ShoppingListsController::class,'index']),
        Route::post('/',[ShoppingListsController::class,'store']),
        Route::get('/{shoppingList}',[ShoppingListsController::class,'show']),
        Route::patch('/{shoppingList}',[ShoppingListsController::class,'update']),
        Route::delete('/{shoppingList}',[ShoppingListsController::class,'destroy']),

        //-----------------------------
        //Shopping list entry routes
        //-----------------------------
        Route::prefix('/{shoppingList}/entries')->group(fn() => [
            Route::get('/',[ShoppingListEntriesController::class,'index']),
            Route::post('/',[ShoppingListEntriesController::class,'store']),
            Route::get('/{shoppingListEntry}',[ShoppingListEntriesController::class,'show']),
            Route::patch('/{shoppingListEntry}',[ShoppingListEntriesController::class,'update']),
            Route::delete('/{shoppingListEntry}',[ShoppingListEntriesController::class,'destroy']),
        ]),
    ]),

    //-----------------------------
    //Grocery products routes
    //-----------------------------
    Route::prefix('/grocery-product')->group(fn() => [
        Route::get('/',[GroceryProductsController::class,'index']),
        Route::post('/',[GroceryProductsController::class,'store']),
        Route::get('/{groceryProduct}',[GroceryProductsController::class,'show']),
        Route::patch('/{groceryProduct}',[GroceryProductsController::class,'update']),
        Route::delete('/{groceryProduct}',[GroceryProductsController::class,'destroy']),

        //-----------------------------
        //Grocery product units routes
        //-----------------------------
        Route::prefix('/{groceryProduct}/units')->group(fn() => [
            Route::get('/',[GroceryProductUnitsController::class,'index']),
            Route::post('/',[GroceryProductUnitsController::class,'store']),
            Route::get('/{groceryProductUnit}',[GroceryProductUnitsController::class,'show']),
            Route::patch('/{groceryProductUnit}',[GroceryProductUnitsController::class,'update']),
            Route::delete('/{groceryProductUnit}',[GroceryProductUnitsController::class,'destroy']),
        ]),
    ])
]);


