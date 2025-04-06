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

        // Log incoming request details for debugging
        Log::info('Journey booking request received', [
            'journey_id' => $request->journey_id,
            'request_format' => $request->header('Content-Type', 'unknown'),
            'has_passenger' => Auth::guard('passenger')->check()
        ]);

        $passengerId = Auth::guard('passenger')->user()->id;
        $journeyId = $request->journey_id;

        // Check if journey exists and has available seats
        $journey = Journey::find($journeyId);
        if (!$journey) {
            Log::warning('Journey not found', ['journey_id' => $journeyId]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Journey not found'
                ], 404);
            }
            return redirect()->back()->with('error', 'Journey not found');
        }

        if ($journey->available_seats <= 0) {
            Log::warning('No available seats for journey', ['journey_id' => $journeyId, 'available_seats' => $journey->available_seats]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No available seats for this journey'
                ], 400);
            }
            return redirect()->back()->with('error', 'No available seats for this journey');
        }

        // Check if the passenger already has a pending request for this journey
        $existingRequest = JourneyRequest::where('journey_id', $journeyId)
            ->where('passenger_id', $passengerId)
            ->first();

        if ($existingRequest) {
            Log::info('Passenger already has a pending request', ['journey_id' => $journeyId, 'passenger_id' => $passengerId]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this journey',
                    'is_booked' => true
                ], 400);
            }
            return redirect()->back()->with('warning', 'You already have a pending request for this journey');
        }

        // Create the journey request
        $journeyRequest = JourneyRequest::create([
            'journey_id' => $journeyId,
            'passenger_id' => $passengerId,
            'status' => 'pending',
        ]);

        Log::info('Journey request created', ['request_id' => $journeyRequest->id]);

        // Get the booking with related data for the notification
        $booking = JourneyRequest::with(['passenger', 'journey.driver'])
            ->where('id', $journeyRequest->id)
            ->first();

        // Notify the driver using Laravel notification
        if ($booking && $booking->journey && $booking->journey->driver) {
            $booking->journey->driver->notify(new NewBookingNotification($booking));
            Log::info('Driver notification sent via Laravel notification', ['driver_id' => $booking->journey->driver->id]);
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

                Log::info('Sending Pusher notification to driver', [
                    'driver_id' => $driverId,
                    'channel' => 'driver-' . $driverId,
                    'event' => 'new-booking',
                    'journey_id' => $journeyId,
                    'booking_id' => $booking->id
                ]);

                $pusher->trigger('driver-' . $driverId, 'new-booking', [
                    'message' => 'New booking request from ' . $passengerName,
                    'journey_id' => $journeyId,
                    'booking_id' => $booking->id,
                    'passenger_id' => $passengerId,
                    'timestamp' => now()->toDateTimeString()
                ]);
            } else {
                Log::warning('Failed to send Pusher notification - Invalid booking data', [
                    'booking_id' => $booking->id ?? 'null',
                    'has_journey' => isset($booking->journey),
                    'has_driver' => isset($booking->journey->driver)
                ]);
            }
        } catch (\Exception $e) {
            // Log the error but continue
            Log::error('Pusher notification error: ' . $e->getMessage(), [
                'exception' => $e,
                'journey_id' => $journeyId,
                'passenger_id' => $passengerId
            ]);
        }

        // Return response based on request type
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Journey booked successfully',
                'request_id' => $journeyRequest->id
            ]);
        }

        return redirect()->back()->with('success', 'Journey booked successfully. You will be notified when the driver responds.');
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
