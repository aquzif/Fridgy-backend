<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarEntriesController;
use App\Http\Controllers\CalendarEntryFastFoodMealsController;
use App\Http\Controllers\FastFoodMealsController;
use App\Http\Controllers\FastFoodMealSetMealsController;
use App\Http\Controllers\FastFoodMealSetsController;
use App\Http\Controllers\FastFoodStoresController;
use App\Http\Controllers\GlobalUnitsController;
use App\Http\Controllers\GroceryProductsController;
use App\Http\Controllers\GroceryProductUnitsController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\ProductUnitController;
use App\Http\Controllers\RecipeIngredientsController;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\RecipeTagsController;
use App\Http\Controllers\ShoppingListEntriesController;
use App\Http\Controllers\ShoppingListsController;
use App\Http\Controllers\UserController;
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



Route::controller(AuthController::class)->group(fn() => [
    //Route::middleware('auth:sanctum')->post('/register','register');
    Route::post('/register','register'),
    Route::post('/login','login'),
    Route::middleware('auth:sanctum')->post('/logout','logout'),
]);


Route::middleware('auth:sanctum')->group(fn() => [

    //-----------------------------
    //User routes
    //-----------------------------

    Route::get('/user',[UserController::class,'index']),
    Route::match(['put','patch'],'/user',[UserController::class,'update']),

    //-----------------------------
    //Shopping list routes
    //-----------------------------
    Route::prefix('/shopping-list')->group(fn() => [
        Route::get('/',[ShoppingListsController::class,'index']),
        Route::post('/',[ShoppingListsController::class,'store']),
        Route::post('/{shoppingList}/insertCalendarEntries',
            [ShoppingListsController::class,'insertCalendarEntries']),
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
                Route::match(['put','patch'],'/{shoppingListEntry}/check',[ShoppingListEntriesController::class,'check']),
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

    //-----------------------------
    //Recipes
    //-----------------------------
    Route::prefix('/recipe')->group(fn() => [
        Route::get('/random',[RecipesController::class,'random']),
        Route::get('/search',[RecipesController::class,'search']),
        Route::get('/',[RecipesController::class,'index']),
        Route::post('/',[RecipesController::class,'store']),
        Route::get('/{recipe}',[RecipesController::class,'show']),
        Route::match(['put','patch'],'/{recipe}',[RecipesController::class,'update']),
        Route::delete('/{recipe}',[RecipesController::class,'destroy']),

        //-----------------------------
        //Recipe ingredients
        //-----------------------------
        Route::prefix('/{recipe}/ingredient')
            ->group(fn() => [
                Route::get('/',[RecipeIngredientsController::class,'index']),
                Route::post('/',[RecipeIngredientsController::class,'store']),
                Route::get('/{ingredient}',[RecipeIngredientsController::class,'show']),
                Route::match(['put','patch'],'/{ingredient}',[RecipeIngredientsController::class,'update']),
                Route::delete('/{ingredient}',[RecipeIngredientsController::class,'destroy']),
            ]),
    ]),

    //-----------------------------
    //Recipe Tags routes
    //-----------------------------
    Route::prefix('/recipe-tag')->group(fn() => [
        Route::get('/',[RecipeTagsController::class,'index']),
        Route::post('/',[RecipeTagsController::class,'store']),
        Route::get('/{recipeTag}',[RecipeTagsController::class,'show']),
        Route::match(['put','patch'],'/{recipeTag}',[RecipeTagsController::class,'update']),
        Route::delete('/{recipeTag}',[RecipeTagsController::class,'destroy']),
    ]),

    //-----------------------------
    //Recipe Calendar Entries routes
    //-----------------------------
    Route::prefix('/calendar-entry')->group(fn() => [
        Route::get('/',[CalendarEntriesController::class,'index']),
        Route::post('/',[CalendarEntriesController::class,'store']),
        Route::get('/{calendarEntry}',[CalendarEntriesController::class,'show']),
        Route::match(['put','patch'],'/{calendarEntry}',[CalendarEntriesController::class,'update']),
        Route::delete('/{calendarEntry}',[CalendarEntriesController::class,'destroy']),

        //-----------------------------
        //Recipe Calendar Entry Fast Food Meals routes
        //-----------------------------
        Route::prefix('/{calendarEntry}/fast-food-meal')
            ->group(fn() => [
                Route::get('/',[CalendarEntryFastFoodMealsController::class,'index']),
                Route::post('/',[CalendarEntryFastFoodMealsController::class,'store']),
                Route::get('/{calendarEntryFastFoodMeal}',[CalendarEntryFastFoodMealsController::class,'show']),
                Route::match(['put','patch'],'/{calendarEntryFastFoodMeal}',[CalendarEntryFastFoodMealsController::class,'update']),
                Route::delete('/{calendarEntryFastFoodMeal}',[CalendarEntryFastFoodMealsController::class,'destroy']),
            ]),

    ]),

    //-----------------------------
    //Fast Food Stores routes
    //-----------------------------
    Route::prefix('/fast-food-store')->group(fn() => [
        Route::get('/',[FastFoodStoresController::class,'index']),
        Route::post('/',[FastFoodStoresController::class,'store']),
        Route::get('/{fastFoodStore}',[FastFoodStoresController::class,'show']),
        Route::match(['put','patch'],'/{fastFoodStore}',[FastFoodStoresController::class,'update']),
        Route::delete('/{fastFoodStore}',[FastFoodStoresController::class,'destroy']),

        //-----------------------------
        //Fast Food Meals routes
        //-----------------------------
        Route::prefix('/{fastFoodStore}/meal')
            ->group(fn() => [
                Route::get('/',[FastFoodMealsController::class,'index']),
                Route::post('/',[FastFoodMealsController::class,'store']),
                Route::get('/{fastFoodMeal}',[FastFoodMealsController::class,'show']),
                Route::match(['put','patch'],'/{fastFoodMeal}',[FastFoodMealsController::class,'update']),
                Route::delete('/{fastFoodMeal}',[FastFoodMealsController::class,'destroy']),
            ]),

        //-----------------------------
        //Fast Food Meal Sets routes
        //-----------------------------
        Route::prefix('/{fastFoodStore}/meal-set')
            ->group(fn() => [
                Route::get('/',[FastFoodMealSetsController::class,'index']),
                Route::post('/',[FastFoodMealSetsController::class,'store']),
                Route::get('/{fastFoodMealSet}',[FastFoodMealSetsController::class,'show']),
                Route::match(['put','patch'],'/{fastFoodMealSet}',[FastFoodMealSetsController::class,'update']),
                Route::delete('/{fastFoodMealSet}',[FastFoodMealSetsController::class,'destroy']),


                Route::prefix('/{fastFoodMealSet}/meal')
                    ->group(fn() => [
                        Route::get('/',[FastFoodMealSetMealsController::class,'index']),
                        Route::post('/',[FastFoodMealSetMealsController::class,'store']),
                        Route::get('/{fastFoodMealInSet}',[FastFoodMealSetMealsController::class,'show']),
                        Route::match(['put','patch'],'/{fastFoodMealInSet}',[FastFoodMealSetMealsController::class,'update']),
                        Route::delete('/{fastFoodMealInSet}',[FastFoodMealSetMealsController::class,'destroy']),
                    ]),

            ]),

    ]),

]);


