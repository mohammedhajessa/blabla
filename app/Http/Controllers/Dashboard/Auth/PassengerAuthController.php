<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class PassengerAuthController extends Controller
{
    public function loginView()
    {
        return view('frontend.passenger.Auth.login');
    }

    public function registerView()
    {
        return view('frontend.passenger.Auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:passengers',
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:255',
        ]);

        $passenger = Passenger::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);
        $passenger->passengerProfile()->create([
            'passenger_id' => $passenger->id,
        ]);
        return redirect()->route('passenger.login')->with('success', 'Passenger registered successfully');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::guard('passenger')->attempt($request->only('email', 'password'))) {
            return redirect()->route('frontpage.index');
        }

        return redirect()->route('passenger.login')->with('error', 'Invalid credentials');
    }

    public function destroy()
    {
        Auth::guard('passenger')->logout();
        return redirect()->route('passenger.login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $passenger = Socialite::driver('google')->stateless()->user();
        $findPassenger = Passenger::where('provider_id', $passenger->id)->first();
        if($findPassenger){
            Auth::login($findPassenger);
            return redirect()->route('passenger.dashboard');
        }
        $newUser = Passenger::updateOrCreate([
            'name' => $passenger->name,
            'email' => $passenger->email,
            'provider' => 'google',
            'provider_id' => $passenger->id,
            'password' => Hash::make(random_int(100000, 999999)),
            'token' => $passenger->token,
            'refresh_token' => $passenger->refreshToken,
        ]);
        Auth::login($newUser);
        return redirect()->route('passenger.dashboard');
    }


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $passenger = Socialite::driver('facebook')->stateless()->user();
        $findPassenger = Passenger::where('provider_id', $passenger->id)->first();
        if($findPassenger){
            Auth::login($findPassenger);
            return redirect()->route('passenger.dashboard');
        }
        $newUser = Passenger::updateOrCreate([
            'name' => $passenger->name,
            'email' => $passenger->email,
            'provider' => 'facebook',
            'provider_id' => $passenger->id,
            'password' => Hash::make(random_int(100000, 999999)),
            'token' => $passenger->token,
            'refresh_token' => $passenger->refreshToken,
        ]);
        Auth::login($newUser);
        return redirect()->route('passenger.dashboard');
    }

}
