<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListsController extends Controller {


    public function index() {
        return 'asd';
    }

    public function store(Request $request) {
        return 'zxc';
    }

    public function show(ShoppingList $shoppingList) {

    }

    public function update(Request $request, ShoppingList $shoppingList) {
    }

    public function destroy(ShoppingList $shoppingList) {
    }
}
