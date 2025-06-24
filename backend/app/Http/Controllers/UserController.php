<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        return $request->user();
    }

    public function update(Request $request) {
        $user = $request->user();
        $fields = $request->validate([
            'name' => 'string',
            'age' => 'numeric',
            'height' => 'numeric',
            'weight' => 'numeric',
            'gender' => 'string|in:male,female',
            'kg_to_lose_per_week' => 'numeric|min:0.1|max:2',
            'physical_activity_coefficient' => 'numeric|min:1|max:2',
            'meals_per_day' => 'numeric|min:1|max:5',
        ]);

        $user->update($fields);
        return $user;
    }

}
