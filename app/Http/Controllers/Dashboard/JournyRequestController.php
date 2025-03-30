<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\DriverApproval;
use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\JourneyRequest;
use App\Models\JourneyPassenger;
use App\Models\Passenger;
use App\Models\Review;
use App\Notifications\BookingResponseNotification;
use App\Notifications\DriverApprovalNotification;
use App\Notifications\JourneyBooked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournyRequestController extends Controller
{
    public function index()
    {
        $journeyRequests = JourneyPassenger::where('passenger_id', Auth::guard('passenger')->user()->id)->whereHas('journey', function($query){
            $query->where('status', 'completed')->orWhere('status', 'pending');
        })->get();
        foreach ($journeyRequests as $journeyRequest) {
            $journeyRequest->hasReview = Review::where('passenger_id', Auth::guard('passenger')->user()->id)
                ->where('journey_id', $journeyRequest->journey_id)
                ->where('driver_id', $journeyRequest->journey->driver_id)
                ->exists();
        }
        
        return view('frontpage.passenger.journey-requests', compact('journeyRequests'));
    }

    public function updateJourneyRequestStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);
        $journeyRequest = JourneyRequest::find($id);
        if($request->status == 'accepted' && $journeyRequest->status == 'pending' && $journeyRequest->journey->driver_id == Auth::guard('driver')->user()->id){
            if($journeyRequest->journey->available_seats > 0){
                $journeyPassenger = JourneyPassenger::where('journey_id', $journeyRequest->journey_id)->where('passenger_id', $journeyRequest->passenger_id)->first();
                if(!$journeyPassenger){
                    JourneyPassenger::updateOrCreate([
                        'journey_id' => $journeyRequest->journey_id,
                        'passenger_id' => $journeyRequest->passenger_id,
                    ]);
                    JourneyRequest::where('journey_id', $journeyRequest->journey_id)->where('passenger_id', $journeyRequest->passenger_id)->update(['status' => 'accepted']);
                }
                $journey = Journey::find($journeyRequest->journey_id);
                $journey->available_seats = $journey->available_seats - 1;
                $journey->save();
                $booking = JourneyRequest::where('journey_id', $journeyRequest->journey_id)->where('passenger_id', $journeyRequest->passenger_id)->first();
                // $booking->notify(new BookingResponseNotification($booking, 'accepted'));
            }else{
                return redirect()->back()->with('error', 'No available seats');
            }
        }else{
            JourneyRequest::where('journey_id', $journeyRequest->journey_id)->where('passenger_id', $journeyRequest->passenger_id)->delete();
            $booking = JourneyRequest::where('journey_id', $journeyRequest->journey_id)->where('passenger_id', $journeyRequest->passenger_id)->first();
            // $booking->notify(new BookingResponseNotification($booking, 'rejected'));
        }

        return redirect()->back()->with('success', 'Journey request status updated successfully');
    }
}
