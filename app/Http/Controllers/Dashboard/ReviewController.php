<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\JourneyPassenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('driver_id', Auth::guard('driver')->user()->id)->get();
        return view('frontend.driver.reviews', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);
        $journeyPassenger = JourneyPassenger::where('passenger_id', Auth::guard('passenger')->user()->id)->where('journey_id', $request->journey_id)->first();
        $review = new Review();
        $review->passenger_id = $journeyPassenger->passenger_id;
        $review->driver_id = $journeyPassenger->journey->driver_id;
        $review->journey_id = $journeyPassenger->journey_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        return redirect()->back()->with('success', 'Review created successfully');
    }

    public function averageRating($driverId)
    {
        $reviews = Review::where('driver_id', $driverId)->get();
        $averageRating = $reviews->avg('rating');
        return $averageRating;
    }

    public function show($id)
    {
        $reviews = Review::where('journey_id', $id)->orderBy('created_at', 'desc')->where('driver_id', Auth::guard('driver')->user()->id)->get();
        return view('frontend.journeys.reviews', compact('reviews'));
    }
}
