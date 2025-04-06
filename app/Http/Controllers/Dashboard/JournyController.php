<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Journey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournyController extends Controller
{
    public function index()
    {
        $journeys = Journey::with('driver', 'pickupCity', 'dropoffCity')->where('driver_id', Auth::guard('driver')->user()->id)->get();
        $cities = City::all();
        return view('frontend.journeys.index', compact('journeys', 'cities'));
    }

    public function create()
    {
        $drivers = Auth::guard('driver')->user();
        $cities = City::all();
        return view('frontend.journeys.create', compact('drivers', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_city_id' => 'required|exists:cities,id',
            'dropoff_city_id' => 'required|exists:cities,id',
            'pickup_address' => 'nullable|string',
            'dropoff_address' => 'nullable|string',
            'pickup_time' => 'nullable|string',
            'arrival_time' => 'nullable|string|after:pickup_time',
            'journey_date' => 'nullable|string',
            'distance' => 'nullable|numeric',
            'available_seats' => 'nullable|integer|min:1|max:' . Auth::guard('driver')->user()->car->no_seats,
            'price' => 'nullable|numeric',
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        Journey::create($request->all());

        return redirect()->route('journeys.index')->with('success', 'Journey created successfully');
    }

    public function edit(Journey $journey)
    {
        $drivers = Auth::guard('driver')->user();
        $journey = Journey::find($journey->id)->where('driver_id', $drivers->id)->where('status', 'pending')->first();
        $cities = City::all();
        return view('frontend.journeys.edit', compact('journey', 'drivers', 'cities'));
    }

    public function update(Request $request, Journey $journey)
    {
        dd($request->all());
        $request->validate([
            'pickup_city_id' => 'required|exists:cities,id',
            'dropoff_city_id' => 'required|exists:cities,id',
            'pickup_address' => 'nullable|string',
            'dropoff_address' => 'nullable|string',
            'pickup_time' => 'nullable|string',
            'arrival_time' => 'nullable|string',
            'journey_date' => 'nullable|string',
            'distance' => 'nullable|numeric',
            'available_seats' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'driver_id' => 'required|exists:drivers,id',
        ]);
        $journey->update($request->all());
        return redirect()->route('journeys.index')->with('success', 'Journey updated successfully');
    }

    public function destroy(Journey $journey)
    {
        $journey->delete();
        return redirect()->route('journeys.index')->with('success', 'Journey deleted successfully');
    }
    public function updateStatus(Request $request, Journey $journey)
    {
        $journey->update(['status' => $request->status]);
        return redirect()->route('journeys.index')->with('success', 'Journey status updated successfully');
    }

    public function show(Journey $journey)
    {
        return view('frontend.journeys.show', compact('journey'));
    }
}
