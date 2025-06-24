<?php

namespace App\Http\Controllers;

use App\Models\CalendarEntry;
use App\Models\CalendarEntryQuickEntry;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class CalendarEntryQuickEntriesController extends Controller
{
    public function index(CalendarEntry $calendarEntry, Request $request)
    {
        return $calendarEntry->calendarEntryQuickEntries;
    }

    public function store(CalendarEntry $calendarEntry, Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'calories' => 'required|numeric'
        ]);

        $calendarEntry->calendarEntryQuickEntries()->create($fields);
        $entry = $calendarEntry->calendarEntryQuickEntries()->latest()->first();
        return ResponseUtils::generateSuccessResponse($entry, 'OK', 201);
    }

    public function show(CalendarEntry $calendarEntry, CalendarEntryQuickEntry $calendarEntryQuickEntry, Request $request)
    {
        return ResponseUtils::generateSuccessResponse($calendarEntryQuickEntry);
    }

    public function update(CalendarEntry $calendarEntry, CalendarEntryQuickEntry $calendarEntryQuickEntry, Request $request)
    {
        $fields = $request->validate([
            'name' => 'string',
            'calories' => 'numeric'
        ]);
        $calendarEntryQuickEntry->update($fields);
        return ResponseUtils::generateSuccessResponse($calendarEntryQuickEntry);
    }

    public function destroy(CalendarEntry $calendarEntry, CalendarEntryQuickEntry $calendarEntryQuickEntry, Request $request)
    {
        $calendarEntryQuickEntry->delete();
        return ResponseUtils::generateSuccessResponse('OK');
    }
}
