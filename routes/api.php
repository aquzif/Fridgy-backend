<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GlobalUnitsController;
use App\Http\Controllers\GroceryProductsController;
use App\Http\Controllers\GroceryProductUnitsController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\ProductUnitController;
    use App\Http\Controllers\ShoppingListEntriesController;
use App\Http\Controllers\ShoppingListsController;
    use App\Models\Product;
    use App\Models\ProductUnit;
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
        Route::get('/',[ShoppingListsController::class,'index']),
        Route::post('/',[ShoppingListsController::class,'store']),
        Route::get('/{shoppingList}',[ShoppingListsController::class,'show']),
        Route::match(['put','patch'],'/{shoppingList}',[ShoppingListsController::class,'update']),
        Route::delete('/{shoppingList}',[ShoppingListsController::class,'destroy']),


        //-----------------------------
        //Shopping list entry routes
        //-----------------------------
        Route::prefix('/{shoppingList}/entry')->middleware('can:view,shoppingList')
            ->group(fn() => [
                Route::get('/',[ShoppingListEntriesController::class,'index']),
                Route::post('/',[ShoppingListEntriesController::class,'store']),
                Route::get('/{shoppingListEntry}',[ShoppingListEntriesController::class,'show']),
                Route::match(['put','patch'],'/{shoppingListEntry}',[ShoppingListEntriesController::class,'update']),
                Route::delete('/{shoppingListEntry}',[ShoppingListEntriesController::class,'destroy']),
            ]),
    ]),

    //-----------------------------
    //Product routes
    //-----------------------------
    Route::prefix('/product')->group(fn() => [
        Route::get('/search',[ProductsController::class,'search']),
        Route::get('/',[ProductsController::class,'index']),
        Route::post('/',[ProductsController::class,'store']),
        Route::get('/{product}',[ProductsController::class,'show']),
        Route::match(['put','patch'],'/{product}',[ProductsController::class,'update']),
        Route::delete('/{product}',[ProductsController::class,'destroy']),


        //-----------------------------
        //Product unit routes
        //-----------------------------
        Route::prefix('/{product}/unit')->middleware('can:view,product')
            ->group(fn() => [
                Route::get('/',[ProductUnitController::class,'index']),
                Route::post('/',[ProductUnitController::class,'store']),
                Route::get('/{productUnit}',[ProductUnitController::class,'show']),
                Route::match(['put','patch'],'/{productUnit}',[ProductUnitController::class,'update']),
                Route::delete('/{productUnit}',[ProductUnitController::class,'destroy']),
        ]),
    ]),

    //-----------------------------
    //Global units routes
    //-----------------------------
    Route::prefix('/global-unit')->group(fn() => [
        Route::get('/',[GlobalUnitsController::class,'index']),
        Route::post('/',[GlobalUnitsController::class,'store']),
        Route::get('/{globalUnit}',[GlobalUnitsController::class,'show']),
        Route::match(['put','patch'],'/{globalUnit}',[GlobalUnitsController::class,'update']),
        Route::delete('/{globalUnit}',[GlobalUnitsController::class,'destroy']),
    ]),

    //-----------------------------
    //Product categories routes
    //-----------------------------
    Route::prefix('/product-category')->group(fn() => [
        Route::get('/',[ProductCategoriesController::class,'index']),
        Route::post('/',[ProductCategoriesController::class,'store']),
        Route::get('/{productCategory}',[ProductCategoriesController::class,'show']),
        Route::match(['put','patch'],'/{productCategory}',[ProductCategoriesController::class,'update']),
        Route::delete('/{productCategory}',[ProductCategoriesController::class,'destroy']),
    ]),

]);


