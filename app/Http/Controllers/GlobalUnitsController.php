<?php

namespace App\Http\Controllers;

use App\Models\GlobalUnit;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class GlobalUnitsController extends Controller {
    public function index() {
        $units = GlobalUnit::all();
        return ResponseUtils::generateSuccessResponse($units);
    }

    public function store(Request $request) {
        $fields = $request->validate([
           'name' => 'string|required|unique:global_units,name',
            'converter' => 'numeric|gt:0|required',
            'default' => 'boolean',
        ]);

        return ResponseUtils::generateSuccessResponse(
            GlobalUnit::create($fields),'OK',201
        );

    }

    public function show(GlobalUnit $globalUnit, Request $request) {
        return ResponseUtils::generateSuccessResponse($globalUnit);
    }

    public function update(Request $request, GlobalUnit $globalUnit) {
        $fields = $request->validate([
            'name' => 'string|unique:global_units,name,' . $globalUnit->id,
            'converter' => 'numeric|gt:0',
            'default' => 'boolean',
        ]);

        $globalUnit->update($fields);
        return ResponseUtils::generateSuccessResponse($globalUnit);

    }

    public function destroy(GlobalUnit $globalUnit) {
        $globalUnit->delete();
        return ResponseUtils::generateSuccessResponse();

    }
}
