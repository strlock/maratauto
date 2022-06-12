<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index(int $state_id)
    {
        try {
            $cityNames = [];
            $query = City::where('state_id', '=', $state_id)->orderBy('name', 'asc');
            foreach($query->get() as $city) {
                $cityNames[$city->id] = $city->name;
            }
            return response()->json($cityNames);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
