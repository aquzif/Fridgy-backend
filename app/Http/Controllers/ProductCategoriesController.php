<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller {

    public function __construct() {
        $this->authorizeResource(ProductCategory::class, 'productCategory');
    }

    public function index() {
        return ResponseUtils::generateSuccessResponse(ProductCategory::all());
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'name' => 'string|required',
        ]);

        $newObj = ProductCategory::create($fields);
        $newObj = $newObj->where('id',$newObj['id'])->first();
        return ResponseUtils::generateSuccessResponse($newObj,'Created',201);
    }

    public function show(ProductCategory $productCategory) {
        return ResponseUtils::generateSuccessResponse($productCategory);
    }


    public function update(Request $request, ProductCategory $productCategory) {
        $fields = $request->validate([
            'name' => 'string',
        ]);

        $productCategory->update($fields);
        return ResponseUtils::generateSuccessResponse($productCategory);
    }

    public function destroy(ProductCategory $productCategory) {
        $productCategory->delete();
        return ResponseUtils::generateSuccessResponse($productCategory);
    }
}
