<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingsController extends Controller
{
    public function index(Request $request) {

        $params = $request->validate([
            'date_from' => 'date|required',
            'date_to' => 'date|required',
        ]);

        return $request->user()->trainings()
            ->where('date', '>=', $params['date_from'])
            ->where('date', '<=', $params['date_to'])
            ->orderBy('date')
            ->get();


    }

    public function store(Request $request) {
        $params = $request->validate([
            'date' => 'date|required',
            'name' => 'string|required',
            'calories' => 'integer|required',
            'duration' => 'integer|required',
        ]);

        $training = $request->user()->trainings()->create($params);

        return response()->json($training, 201);
    }

    public function show(Request $request, $id) {
        return $request->user()->trainings()->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $params = $request->validate([
            'date' => 'date|required',
            'name' => 'string|required',
            'calories' => 'integer|required',
            'duration' => 'integer|required',
        ]);

        $training = $request->user()->trainings()->findOrFail($id);
        $training->update($params);

        return response()->json($training, 200);
    }

    public function destroy(Request $request, $id) {
        $training = $request->user()->trainings()->findOrFail($id);
        $training->delete();

        return response()->json(null, 204);
    }
}
