<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroceryProductsController;
use App\Http\Controllers\GroceryProductUnitsController;
use App\Http\Controllers\ShoppingListEntriesController;
use App\Http\Controllers\ShoppingListsController;
use App\Models\ShoppingList;
use App\Models\ShoppingListEntry;
use App\Models\User;
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
        Route::get('/',[ShoppingListsController::class,'index'])->can('viewAny',ShoppingList::class),
        Route::post('/',[ShoppingListsController::class,'store'])->can('create',ShoppingList::class),
        Route::get('/{shoppingList}',[ShoppingListsController::class,'show'])->can('view','shoppingList'),
        Route::match(['put','patch'],'/{shoppingList}',[ShoppingListsController::class,'update'])->can('update','shoppingList'),
        Route::delete('/{shoppingList}',[ShoppingListsController::class,'destroy'])->can('delete','shoppingList'),

        //-----------------------------
        //Shopping list entry routes
        //-----------------------------
        Route::prefix('/{shoppingList}/entry')->middleware('can:view,shoppingList')->group(fn() => [
            Route::get('/',[ShoppingListEntriesController::class,'index'])->can('viewAny',ShoppingListEntry::class),
            Route::post('/',[ShoppingListEntriesController::class,'store'])->can('create',ShoppingListEntry::class),
            Route::get('/{shoppingListEntry}',[ShoppingListEntriesController::class,'show'])->can('view',['shoppingList','shoppingListEntry']),
            Route::match(['put','patch'],'/{shoppingListEntry}',[ShoppingListEntriesController::class,'update'])->can('update',['shoppingList','shoppingListEntry']),
            Route::delete('/{shoppingListEntry}',[ShoppingListEntriesController::class,'destroy'])->can('delete',['shoppingList','shoppingListEntry']),
        ]),
    ]),


]);


