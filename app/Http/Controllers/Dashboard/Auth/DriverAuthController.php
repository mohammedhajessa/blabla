<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $driver = Driver::where('email', $request->email)->first();
        if(!$driver || $driver->registration_status !== 'approved'){
            return back()->with('error', 'Login failed. Your account is not approved yet or credentials are invalid.');
        }
        if(Auth::guard('driver')->attempt($request->only('email', 'password'))){
            $request->session()->regenerate();
            return redirect()->intended(route('driver.dashboard', absolute: false));
        }
        return back()->with('error', 'The provided credentials do not match our records.');
    }
    public function destroy(Request $request)
    {
        Auth::guard('driver')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('driver.login');
    }
}
