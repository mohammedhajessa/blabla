<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\JourneyPassenger;
use App\Models\Passenger;
use App\Trait\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PassengerController extends Controller
{
    use StoreImage;

    public function index()
    {
        $passengers = Passenger::all();
        return view('frontend.passenger.index', compact('passengers'));
    }

    public function show($id)
    {
        $passenger = Passenger::find($id);
        return view('frontend.passenger.show', compact('passenger'));
    }

    public function destroy($id)
    {
        $passenger = Passenger::find($id);
        $passenger->delete();
        return redirect()->route('passengers.index')->with('success', 'Passenger deleted successfully');
    }

    public function dashboard()
    {
        return view('frontpage.passenger.dashboard');
    }

    public function profile()
    {
        $passenger = Auth::guard('passenger')->user();
        $passengerProfile = $passenger->passengerProfile;
        $journeyPassengers = JourneyPassenger::where('passenger_id', $passenger->id)->get();
        $currentJourney = $journeyPassengers->where('status', 'pending')->first();
        $recentJourneys = $journeyPassengers->where('status', 'completed')->first();
        return view('frontpage.passenger.profile', compact('passenger', 'passengerProfile', 'journeyPassengers', 'currentJourney', 'recentJourneys'));
    }

    public function editProfile()
    {
        $passenger = Auth::guard('passenger')->user();
        return view('frontpage.passenger.editProfile', compact('passenger'));
    }

    public function updateProfile(Request $request)
    {
        $passenger = Auth::guard('passenger')->user();
        $passengerProfile = $passenger->passengerProfile;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:passengers,email,' . $passenger->id,
            'phone' => 'required|string|max:255|unique:passengers,phone,' . $passenger->id,
            'password' => 'nullable|string|min:8',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'age' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'identification_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'identification_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $passenger->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        if($request->password){
            $passenger->password = Hash::make($request->password);
            $passenger->save();
        }
        $passenger->passengerProfile()->update([
            'city' => $request->city,
            'region' => $request->region,
            'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
        ]);
        if ($request->hasFile('identification_front_image')) {
            if($passengerProfile->identification_front_image){
                $this->deleteImage($passengerProfile->identification_front_image);
            }
            $passengerProfile->identification_front_image = $this->storeImage($request->file('identification_front_image'), 'passenger_profiles');
        }
        if ($request->hasFile('identification_back_image')) {
            if($passengerProfile->identification_back_image){
                $this->deleteImage($passengerProfile->identification_back_image);
            }
            $passengerProfile->identification_back_image = $this->storeImage($request->file('identification_back_image'), 'passenger_profiles');
        }
        if ($request->hasFile('profile_picture')) {
            if($passengerProfile->profile_picture){
                $this->deleteImage($passengerProfile->profile_picture);
            }
            $passengerProfile->profile_picture = $this->storeImage($request->file('profile_picture'), 'passenger_profiles');
        }
        $passengerProfile->save();
        $passenger->save();
        return redirect()->route('passenger.profile')->with('success', 'Profile updated successfully');
    }
}
