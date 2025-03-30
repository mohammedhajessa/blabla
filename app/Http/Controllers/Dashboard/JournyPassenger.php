<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\JourneyPassenger;
use App\Models\JourneyRequest;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class JournyPassenger extends Controller
{
    public function index()
    {
        $driver = Auth::guard('driver')->user();
        $journeyPassengers = JourneyPassenger::whereHas('journey', function($query) use ($driver){
            $query->where('driver_id', $driver->id);
        })->get();
        return view('frontend.driver.journey-passengers', compact('journeyPassengers'));
    }

    public function show($id)
    {
        $passenger = Passenger::find($id);
        return view('frontend.driver.journey-passenger', compact('passenger'));
    }

    public function destroy($id)
    {

        $journeyPassenger = JourneyPassenger::find($id);

        if (!$journeyPassenger) {
            return redirect()->back()->with('error', 'Passenger not found');
        }
        $journey = Journey::find($journeyPassenger->journey_id);

        if ($journey) {
            $journey->available_seats = $journey->available_seats + 1;
            $journey->save();
        }
        $journeyPassenger->delete();
        return redirect()->back()->with('success', 'Passenger removed from journey');
    }
}
