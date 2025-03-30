<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Journey;
use App\Models\JourneyRequest;
use App\Events\JournyBooked;
use App\Events\PassengerBooking;
use App\Notifications\NewBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class FrontPageController extends Controller
{
    public function index()
    {
        $cities = City::with('region')->get();
        return view('frontpage.index', compact('cities'));
    }

    public function searchJourneys(Request $request)
    {
        $journeys = Journey::where('pickup_city_id', $request->pickup_city)
            ->where('dropoff_city_id', $request->dropoff_city)
            ->where('journey_date', $request->journey_date)
            ->where('status', 'pending')
            ->where('available_seats', '>', 0);

        if (Auth::guard('passenger')->check()) {
            $passengerId = Auth::guard('passenger')->id();

            $journeys = $journeys->whereNotIn('id', function($query) use ($passengerId) {
                $query->select('journey_id')
                    ->from('journey_requests')
                    ->where('passenger_id', $passengerId);
            });
        }

        return response()->json($journeys->get());
    }

    public function bookJourney(Request $request)
    {
        // Validate request
        $request->validate([
            'journey_id' => 'required|exists:journeys,id',
        ]);

        $passengerId = Auth::guard('passenger')->user()->id;
        $journeyId = $request->journey_id;

        // Check if journey exists and has available seats
        $journey = Journey::find($journeyId);
        if (!$journey) {
            return response()->json([
                'success' => false,
                'message' => 'Journey not found'
            ], 404);
        }

        if ($journey->available_seats <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No available seats for this journey'
            ], 400);
        }

        // Check if the passenger already has a pending request for this journey
        $existingRequest = JourneyRequest::where('journey_id', $journeyId)
            ->where('passenger_id', $passengerId)
            ->first();

        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending request for this journey',
                'is_booked' => true
            ], 400);
        }

        // Create the journey request
        $journeyRequest = JourneyRequest::create([
            'journey_id' => $journeyId,
            'passenger_id' => $passengerId,
            'status' => 'pending',
        ]);

        // Get the booking with related data for the notification
        $booking = JourneyRequest::with(['passenger', 'journey.driver'])
            ->where('id', $journeyRequest->id)
            ->first();

        // Notify the driver using Laravel notification
        if ($booking && $booking->journey && $booking->journey->driver) {
            $booking->journey->driver->notify(new NewBookingNotification($booking));
        }

        // Send real-time notification to driver using Pusher
        try {
            $pusher = new \Pusher\Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                ]
            );

            if ($booking && $booking->journey && $booking->journey->driver) {
                $driverId = $booking->journey->driver->id;
                $passengerName = $booking->passenger->name ?? 'Passenger';

                $pusher->trigger('driver-' . $driverId, 'new-booking', [
                    'message' => 'New booking request from ' . $passengerName,
                    'journey_id' => $journeyId,
                    'booking_id' => $booking->id,
                    'passenger_id' => $passengerId,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }
        } catch (\Exception $e) {
            // Log the error but continue
            Log::error('Pusher notification error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Journey booked successfully',
            'request_id' => $journeyRequest->id
        ]);
    }

    public function checkPendingRequests()
    {
        // Make sure the user is logged in as a passenger
        if (!Auth::guard('passenger')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $passengerId = Auth::guard('passenger')->id();

        // Get all pending journey requests for this passenger
        $pendingRequests = JourneyRequest::where('passenger_id', $passengerId)
            ->where('status', 'pending')
            ->with(['journey:id,pickup_city_id,dropoff_city_id,journey_date,pickup_time,price,available_seats,driver_id'])
            ->get();

        return response()->json([
            'success' => true,
            'pending_requests' => $pendingRequests
        ]);
    }
}
