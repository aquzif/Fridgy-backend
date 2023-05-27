<?php

namespace App\Http\Controllers;

use App\Models\GroceryProduct;
use Illuminate\Http\Request;

class GroceryProductsController extends Controller {


    public function index() {
        return 'asd';
    }

    public function store(Request $request) {
        return 'zxc';
    }

    public function show(GroceryProduct $groceryProduct) {

    }

    public function update(Request $request, GroceryProduct $groceryProduct) {
    }

    public function destroy(GroceryProduct $groceryProduct) {
    }
}
