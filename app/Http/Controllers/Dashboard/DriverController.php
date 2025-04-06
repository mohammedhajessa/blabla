<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Car;
use App\Models\City;
use App\Models\Journey;
use App\Models\JourneyRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Trait\StoreImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    use StoreImage;
    public function index()
    {
        $drivers = Driver::with('driverProfile')->get();
        return view('frontend.driver.index', compact('drivers'));
    }

    public function create()
    {
        $cars = Car::all();
        $cities = City::all();
        return view('frontend.driver.create', compact('cars', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers',
            'password' => 'required|min:8',
            'phone' => 'required|string|unique:driver_profiles',
            'address' => 'required|string',
            'gender' => 'required|in:male,female',
            'license_number' => 'nullable|string|unique:driver_profiles',
            'license_expiry_date' => 'nullable|string',
            'license_front_image' => 'nullable|image',
            'license_back_image' => 'nullable|image',
            'identity_front_image' => 'nullable|image',
            'identity_back_image' => 'nullable|image',
            'identity_number' => 'nullable|string|unique:driver_profiles',
        ]);
        $driver = Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'registration_status' => 'pending',
            'status' => 'inactive',
        ]);

        $profileData = [
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'age' => $request->age,
            'license_number' => $request->license_number,
            'license_expiry_date' => $request->license_expiry_date,
            'identity_number' => $request->identity_number,
            'driver_id' => $driver->id,
        ];
        if ($request->hasFile('license_front_image')) {
            $profileData['license_front_image'] = $this->storeImage($request->file('license_front_image'), 'driver_profiles');
        }
        if ($request->hasFile('license_back_image')) {
            $profileData['license_back_image'] = $this->storeImage($request->file('license_back_image'), 'driver_profiles');
        }
        if ($request->hasFile('identity_front_image')) {
            $profileData['identity_front_image'] = $this->storeImage($request->file('identity_front_image'), 'driver_profiles');
        }
        if ($request->hasFile('identity_back_image')) {
            $profileData['identity_back_image'] = $this->storeImage($request->file('identity_back_image'), 'driver_profiles');
        }
        $driver->driverProfile()->create($profileData);

        return redirect()->route('driver.login')->with('success', 'Driver created successfully');
    }

    public function login()
    {
        return view('frontend.driver.auth.login');
    }

    public function register()
    {
        $cities = City::with('region')->get();
        return view('frontend.driver.auth.register', compact('cities'));
    }

    public function updateRegisterStatus(Request $request, $id)
    {
        $driver = Driver::find($id);
        $driver->update([
            'registration_status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Driver registration status updated successfully');
    }

    public function dashboard()
    {
        $driver = Auth::guard('driver')->user();
        $car = Car::where('driver_id', $driver->id)->first();

        // Get total trips
        $totalTrips = Journey::where('driver_id', $driver->id)->count();

        // Get total earnings
        $totalEarnings = Journey::where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->sum('price');

        // Calculate average rating
        $rating = Journey::where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->avg('price') ?? 0;

        // Get recent trips
        $recentTrips = Journey::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Get last completed journey with passenger
        $lastJourney = Journey::where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->latest()
            ->first();
        $avarageRating = Review::where('driver_id', $driver->id)->avg('rating');
        // Get upcoming schedules
        $upcomingSchedules = Journey::where('driver_id', $driver->id)
            ->where('status', 'scheduled')
            ->where('journey_date', '>=', now())
            ->orderBy('journey_date')
            ->orderBy('pickup_time')
            ->get();

        return view('frontend.driver.dashboard', compact(
            'driver',
            'car',
            'totalTrips',
            'totalEarnings',
            'avarageRating',
            'recentTrips',
            'lastJourney',
            'upcomingSchedules'
        ));
    }

    public function profile()
    {
        $driver = Auth::guard('driver')->user();
        return view('frontend.driver.profile', compact('driver'));
    }

    public function editProfile()
    {
        $driver = Auth::guard('driver')->user();
        return view('frontend.driver.edit-profile', compact('driver'));
    }

    public function updateProfile(Request $request)
    {
        $driver = Auth::guard('driver')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'phone' => 'required|string|unique:driver_profiles,phone,' . $driver->id,
            'password' => 'nullable|min:8',
            'address' => 'required|string',
            'gender' => 'required|in:male,female',
            'license_number' => 'nullable|string|unique:driver_profiles,license_number,' . $driver->id,
            'license_expiry_date' => 'nullable|string',
            'license_front_image' => 'nullable|image',
            'license_back_image' => 'nullable|image',
            'identity_front_image' => 'nullable|image',
            'identity_back_image' => 'nullable|image',
            'identity_number' => 'nullable|string|unique:driver_profiles,identity_number,' . $driver->id,
            'status' => 'required|in:active,inactive',
        ]);
        $driver->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);
        if($request->password){
            $driver->password = Hash::make($request->password);
            $driver->save();
        }
        $driver->driverProfile()->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'age' => $request->age,
            'license_number' => $request->license_number,
            'license_expiry_date' => $request->license_expiry_date,
            'identity_number' => $request->identity_number,
        ]);
        if ($request->hasFile('license_front_image')) {
            if($driver->driverProfile->license_front_image){
                $this->deleteImage($driver->driverProfile->license_front_image);
            }
            $driver->driverProfile->license_front_image = $this->storeImage($request->file('license_front_image'), 'driver_profiles');
        }
        if ($request->hasFile('license_back_image')) {
            if($driver->driverProfile->license_back_image){
                $this->deleteImage($driver->driverProfile->license_back_image);
            }
            $driver->driverProfile->license_back_image = $this->storeImage($request->file('license_back_image'), 'driver_profiles');
        }
        if ($request->hasFile('identity_front_image')) {
            if($driver->driverProfile->identity_front_image){
                $this->deleteImage($driver->driverProfile->identity_front_image);
            }
            $driver->driverProfile->identity_front_image = $this->storeImage($request->file('identity_front_image'), 'driver_profiles');
        }
        if ($request->hasFile('identity_back_image')) {
            if($driver->driverProfile->identity_back_image){
                $this->deleteImage($driver->driverProfile->identity_back_image);
            }
            $driver->driverProfile->identity_back_image = $this->storeImage($request->file('identity_back_image'), 'driver_profiles');
        }
        if($request->hasFile('image')){
            if($driver->driverProfile->image){
                $this->deleteImage($driver->driverProfile->image);
            }
            $driver->driverProfile->image = $this->storeImage($request->file('image'), 'driver_profiles/image');
        }
        $driver->driverProfile->save();
        $driver->save();

        return redirect()->route('driver.profile')->with('success', 'Profile updated successfully');
    }

    public function cars()
    {
        $driver = Auth::guard('driver')->user();
        $car = Car::where('driver_id', $driver->id)->first();
        return view('frontend.driver.cars', compact('car'));
    }

    public function createCar()
    {
        $driver = Auth::guard('driver')->user();
        $existingCar = Car::where('driver_id', $driver->id)->first();

        if ($existingCar) {
            return redirect()->route('driver.cars')->with('error', 'You already have a car registered.');
        }
        return view('frontend.car.create');
    }

    public function journeyRequests()
    {
        $driver = Auth::guard('driver')->user();
        $journeyRequests = JourneyRequest::whereHas('journey', function($query) use ($driver){
            $query->where('driver_id', $driver->id);
        })->with('passenger')->where('status', 'pending')->get();
        return view('frontend.driver.journey-requests', compact('journeyRequests'));
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully');
    }
}
