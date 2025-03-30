<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('region')->get();
        return view('frontend.city.index', compact('cities'));
    }

    public function create()
    {
        $cities = City::with('region')->get();
        return view('frontend.city.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region_id' => 'nullable|exists:cities,id',
        ]);
        City::create($request->all());
        return redirect()->route('cities.index')->with('success', 'City created successfully');
    }

    public function edit(City $city)
    {
        $cities = City::with('region')->get();
        return view('frontend.city.edit', compact('city', 'cities'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region_id' => 'nullable|exists:cities,id',
        ]);
        $city->update($request->all());
        return redirect()->route('cities.index')->with('success', 'City updated successfully');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('cities.index')->with('success', 'City deleted successfully');
    }
}
