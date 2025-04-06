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
use Illuminate\Support\Facades\Log;

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
        $passengerId = $journeyRequest->passenger_id;
        $journeyId = $journeyRequest->journey_id;
        $statusAction = $request->status;

        if($statusAction == 'accepted' && $journeyRequest->status == 'pending' && $journeyRequest->journey->driver_id == Auth::guard('driver')->user()->id){
            if($journeyRequest->journey->available_seats > 0){
                $journeyPassenger = JourneyPassenger::where('journey_id', $journeyId)->where('passenger_id', $passengerId)->first();
                if(!$journeyPassenger){
                    JourneyPassenger::updateOrCreate([
                        'journey_id' => $journeyId,
                        'passenger_id' => $passengerId,
                    ]);
                    JourneyRequest::where('journey_id', $journeyId)->where('passenger_id', $passengerId)->update(['status' => 'accepted']);
                }
                $journey = Journey::find($journeyId);
                $journey->available_seats = $journey->available_seats - 1;
                $journey->save();
                $booking = JourneyRequest::where('journey_id', $journeyId)->where('passenger_id', $passengerId)->first();
                // Send notification to passenger
                $this->sendNotificationToPassenger($passengerId, $journeyId, 'accepted', 'Your journey request has been accepted! You have successfully booked this journey.');
            }else{
                return redirect()->back()->with('error', 'No available seats');
            }
        }else if($statusAction == 'rejected' && $journeyRequest->status == 'pending'){
            // Update status to rejected
            JourneyRequest::where('journey_id', $journeyId)->where('passenger_id', $passengerId)->update(['status' => 'rejected']);

            // Send notification to passenger
            $this->sendNotificationToPassenger($passengerId, $journeyId, 'rejected', 'Your journey request has been rejected. Please try booking another journey.');
        }

        return redirect()->back()->with('success', 'Journey request status updated successfully');
    }

    /**
     * Send Pusher notification to passenger
     *
     * @param int $passengerId
     * @param int $journeyId
     * @param string $status
     * @param string $message
     * @return void
     */
    private function sendNotificationToPassenger($passengerId, $journeyId, $status, $message)
    {
        try {
            $pusherKey = env('PUSHER_APP_KEY');
            $pusherSecret = env('PUSHER_APP_SECRET');
            $pusherAppId = env('PUSHER_APP_ID');
            $pusherCluster = env('PUSHER_APP_CLUSTER');

            // Log Pusher configuration for debugging
            Log::info('Pusher configuration', [
                'has_key' => !empty($pusherKey),
                'has_secret' => !empty($pusherSecret),
                'has_app_id' => !empty($pusherAppId),
                'cluster' => $pusherCluster
            ]);

            $pusher = new \Pusher\Pusher(
                $pusherKey,
                $pusherSecret,
                $pusherAppId,
                [
                    'cluster' => $pusherCluster,
                    'useTLS' => true
                ]
            );

            $driverName = Auth::guard('driver')->user()->name ?? 'Driver';
            $channelName = 'passenger-' . $passengerId;

            Log::info('Sending Pusher notification to passenger', [
                'passenger_id' => $passengerId,
                'channel' => $channelName,
                'event' => 'booking-response',
                'journey_id' => $journeyId,
                'status' => $status
            ]);

            $data = [
                'journey_id' => $journeyId,
                'status' => $status,
                'message' => $message,
                'driver_name' => $driverName,
                'timestamp' => now()->toDateTimeString()
            ];

            $result = $pusher->trigger($channelName, 'booking-response', $data);

            Log::info('Pusher notification result', [
                'success' => $result,
                'channel' => $channelName
            ]);

            return $result;
        } catch (\Exception $e) {
            // Log the error but continue
            Log::error('Pusher notification error: ' . $e->getMessage(), [
                'exception' => $e,
                'passenger_id' => $passengerId,
                'journey_id' => $journeyId
            ]);

            return false;
        }
    }
}
