<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\Auth\DriverAuthController;
use App\Http\Controllers\Dashboard\Auth\PassengerAuthController;
use App\Http\Controllers\Dashboard\CarController;
use App\Http\Controllers\Dashboard\CityController;
use App\Http\Controllers\Dashboard\DriverController;
use App\Http\Controllers\Dashboard\FrontPageController;
use App\Http\Controllers\Dashboard\JournyController;
use App\Http\Controllers\Dashboard\JournyPassenger;
use App\Http\Controllers\Dashboard\JournyRequestController;
use App\Http\Controllers\Dashboard\PassengerController;
use App\Http\Controllers\Dashboard\ReviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/register-driver', [DriverController::class,'register'])->name('driver.register');
Route::post('/register-driver', [DriverController::class,'store'])->name('driver.store');
Route::get('/login-driver', [DriverController::class,'login'])->name('driver.login');
Route::post('/login-driver', [DriverAuthController::class,'login'])->name('driver.login');

Route::get('/login-passenger', [PassengerAuthController::class, 'loginView'])->name('passenger.login');
Route::post('/login-passenger', [PassengerAuthController::class, 'login'])->name('passenger.login');
Route::get('/register-passenger', [PassengerAuthController::class, 'registerView'])->name('passenger.register');
Route::post('/register-passenger', [PassengerAuthController::class, 'register'])->name('passenger.register');

//Google Auth
Route::get('/auth/google', [PassengerAuthController::class, 'redirectToGoogle'])->name('passenger.auth.google');
Route::get('/auth/google/callback', [PassengerAuthController::class, 'handleGoogleCallback'])->name('passenger.auth.google.callback');

//Facebook Auth
// Route::get('/auth/facebook', [PassengerAuthController::class, 'redirectToFacebook'])->name('passenger.auth.facebook');
// Route::get('/auth/facebook/callback', [PassengerAuthController::class, 'handleFacebookCallback'])->name('passenger.auth.facebook.callback');


Route::middleware('driver')->group(function(){
    Route::resource('cars', CarController::class)->except('index')->except('show');
    Route::get('/driver-dashboard', [DriverController::class,'dashboard'])->name('driver.dashboard');
    Route::get('/driver-profile', [DriverController::class,'profile'])->name('driver.profile');
    Route::get('/driver-edit-profile', [DriverController::class,'editProfile'])->name('driver.editProfile');
    Route::post('/driver-logout', [DriverAuthController::class,'destroy'])->name('driver.logout');
    Route::get('/driver-edit-profile', [DriverController::class,'editProfile'])->name('driver.editProfile');
    Route::put('/driver-update-profile', [DriverController::class,'updateProfile'])->name('driver.updateProfile');
    Route::get('/driver-cars', [DriverController::class,'cars'])->name('driver.cars');
    Route::get('/driver-create-car', [DriverController::class,'createCar'])->name('driver.createCar');
    Route::post('/driver-create-car', [DriverController::class,'storeCar'])->name('driver.storeCar');
    Route::resource('journeys', JournyController::class);
    Route::put('/journeys/update-status/{journey}', [JournyController::class,'updateStatus'])->name('journeys.updateStatus');
    Route::put('/driver-journey-requests/update-status/{journeyRequest}', [JournyRequestController::class,'updateJourneyRequestStatus'])->name('driver.journeyRequests.updateStatus');
    // Route::put('/driver-journey-requests/update-status/{journeyRequest}', [DriverController::class,'updateJourneyRequestStatus'])->name('driver.journeyRequests.updateStatus');

    // Driver Journey Passengers
    Route::get('/driver-journey-requests', [DriverController::class,'journeyRequests'])->name('driver.journeyRequests');
    Route::get('/driver-journey-passengers', [JournyPassenger::class,'index'])->name('driver.journeyPassengers');
    Route::get('/driver-journey-passenger/{id}', [JournyPassenger::class,'show'])->name('driver.journeyPassenger');
    Route::delete('/driver-journey-passenger/{id}', [JournyPassenger::class,'destroy'])->name('driver.journeyPassenger.destroy');


    // Driver Reviews
    Route::get('/driver-reviews', [ReviewController::class,'index'])->name('driver.reviews');
    Route::get('/driver-reviews/{id}', [ReviewController::class,'show'])->name('driver.reviews.show');
});
Route::middleware(['auth', 'admin'])->group(function(){
    Route::resource('cities', CityController::class);
    Route::put('/driver-update-status/{id}', [DriverController::class,'updateRegisterStatus'])->name('driver.updateStatusRegister');
    Route::get('/cars', [CarController::class,'index'])->name('cars.index');
    Route::get('/cars/{id}', [CarController::class,'show'])->name('cars.show');
    Route::get('/drivers', [DriverController::class,'index'])->name('drivers.index');
    Route::get('/drivers/{id}', [DriverController::class,'show'])->name('drivers.show');
    Route::delete('/drivers/{id}', [DriverController::class,'destroy'])->name('drivers.destroy');
    Route::get('/passengers', [PassengerController::class,'index'])->name('passengers.index');
    Route::get('/passengers/{id}', [PassengerController::class,'show'])->name('passengers.show');
    Route::delete('/passengers/{id}', [PassengerController::class,'destroy'])->name('passengers.destroy');

});

Route::middleware('passenger')->group(function(){
    Route::get('/passenger-dashboard', [PassengerController::class,'dashboard'])->name('passenger.dashboard');
    Route::get('/passenger-profile', [PassengerController::class,'profile'])->name('passenger.profile');
    Route::get('/passenger-edit-profile', [PassengerController::class,'editProfile'])->name('passenger.editProfile');
    Route::put('/passenger-update-profile', [PassengerController::class,'updateProfile'])->name('passenger.updateProfile');
    Route::post('/passenger-logout', [PassengerAuthController::class,'destroy'])->name('passenger.logout');
    Route::get('/passenger-journey-requests', [JournyRequestController::class,'index'])->name('passenger.journeyRequests');
    Route::post('/passenger-review', [ReviewController::class,'store'])->name('passenger.review');
    Route::post('/passenger-book-journey', [FrontPageController::class, 'bookJourney'])->name('passenger.bookJourney');
});

Route::get('/', [FrontPageController::class, 'index'])->name('frontpage.index');
Route::get('/search-journeys', [FrontPageController::class, 'searchJourneys'])->name('frontpage.searchJourneys');
Route::post('/book-journey', [FrontPageController::class, 'bookJourney'])->name('frontpage.bookJourney');
Route::get('/check-pending-requests', [FrontPageController::class, 'checkPendingRequests'])->name('frontpage.checkPendingRequests');
