<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Trait\StoreImage;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    use StoreImage;
    public function index()
    {
        $cars = Car::with('images','driver')->get();
        return view('frontend.car.index', compact('cars'));
    }

    public function create()
    {
        $drivers = Driver::all();
        return view('frontend.car.create', compact('drivers'));
    }

    public function show($id)
    {
        $car = Car::with('images','driver')->find($id);
        $drivers = Driver::all();

        // dd($car);
        return view('frontend.car.show', compact('car', 'drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'driver_id' => 'required|exists:drivers,id',
            'no_plat' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'no_seats' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'fuel_type' => 'required|in:bensin,solar,diesel',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $car = Car::create($request->except('images'));
        if ($request->hasFile('images')) {
            $this->storeImages($request, $car, 'uploads/cars');
        }
        return redirect()->route('driver.cars')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Car $car)
    {
        $images = $car->images;
        $drivers = Driver::all();
        return view('frontend.car.edit', compact('car', 'images', 'drivers'));
    }

    public function update(Request $request, Car $car)
    {
        $request->validate(Car::rules());
        $car->update($request->except('images'));
        if ($request->hasFile('images')) {
            $this->deleteImages($car);
            $this->storeImages($request, $car, 'uploads/cars');
        }

        return redirect()->route('driver.cars')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Car $car)
    {
        if ($car->images()->exists()) {
            $this->deleteImages($car);
        }
        $car->delete();
        $driver = Auth::guard('driver')->user();
        $car = Car::where('driver_id', $driver->id)->first();
        if($car){
            $car->delete();
        }
        return redirect()->route('cars.index')->with('success', 'Data berhasil dihapus');
    }
}
