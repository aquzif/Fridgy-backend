<?php

namespace App\Http\Controllers;

use App\Models\ShelfEntry;
use App\Models\GlobalUnit;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class ShelfEntriesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ShelfEntry::class, 'shelfEntry');
    }

    public function index(Request $request)
    {
        return ResponseUtils::generateSuccessResponse($request->user()->shelfEntries);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'product_name' => 'string|required',
            'amount' => 'numeric',
            'unit_id' => 'numeric|exists:global_units,id|nullable',
            'bought_at' => 'date|nullable',
            'expires_at' => 'date|nullable',
        ]);

        if(isset($fields['unit_id'])) {
            $unit = GlobalUnit::find($fields['unit_id']);
            $fields['unit_name'] = $unit->name;
        }

        $entry = $request->user()->shelfEntries()->create($fields);

        return ResponseUtils::generateSuccessResponse($entry, 'OK', 201);
    }

    public function show(Request $request, ShelfEntry $shelfEntry)
    {
        return ResponseUtils::generateSuccessResponse($shelfEntry);
    }

    public function update(Request $request, ShelfEntry $shelfEntry)
    {
        $fields = $request->validate([
            'product_name' => 'string',
            'amount' => 'numeric',
            'unit_id' => 'numeric|exists:global_units,id|nullable',
            'bought_at' => 'date|nullable',
            'expires_at' => 'date|nullable',
        ]);

        if(isset($fields['unit_id'])) {
            $unit = GlobalUnit::find($fields['unit_id']);
            $fields['unit_name'] = $unit->name;
        }

        $shelfEntry->update($fields);

        return ResponseUtils::generateSuccessResponse($shelfEntry);
    }

    public function destroy(ShelfEntry $shelfEntry)
    {
        $shelfEntry->delete();
        return ResponseUtils::generateSuccessResponse();
    }
}
