@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Edit Car</h5>
                    <div class="card-body">
                        <form action="{{ route('cars.update', $car->id) }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="name">Car Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Toyota Avanza" required value="{{ old('name', $car->name) }}" />
                                <div class="invalid-feedback">Please enter car name.</div>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="no_plat">License Plate</label>
                                <input type="text" class="form-control" id="no_plat" name="no_plat"
                                    placeholder="B 1234 ABC" required value="{{ old('no_plat', $car->no_plat) }}" />
                                <div class="invalid-feedback">Please enter license plate number.</div>
                                @error('no_plat')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="year">Year</label>
                                <input type="number" class="form-control" id="year" name="year"
                                    placeholder="{{ date('Y') }}" required value="{{ old('year', $car->year) }}" />
                                <div class="invalid-feedback">Please enter car year.</div>
                                @error('year')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="no_seats">Number of Seats</label>
                                <input type="number" class="form-control" id="no_seats" name="no_seats" placeholder="7"
                                    required value="{{ old('no_seats', $car->no_seats) }}" />
                                <div class="invalid-feedback">Please enter number of seats.</div>
                                @error('no_seats')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="fuel_type">Fuel Type</label>
                                <select class="form-select" id="fuel_type" name="fuel_type" required>
                                    <option value="">Select Fuel Type</option>
                                    <option value="bensin" {{ old('fuel_type', $car->fuel_type) == 'bensin' ? 'selected' : '' }}>Bensin
                                    </option>
                                    <option value="solar" {{ old('fuel_type', $car->fuel_type) == 'solar' ? 'selected' : '' }}>Solar
                                    </option>
                                    <option value="diesel" {{ old('fuel_type', $car->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel
                                    </option>
                                </select>
                                <div class="invalid-feedback">Please select fuel type.</div>
                                @error('fuel_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="note">Notes</label>
                                <textarea class="form-control" id="note" name="note" rows="3">{{ old('note', $car->note) }}</textarea>
                                @error('note')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="card">
                                    <h5 class="card-header">Car Images</h5>
                                    <div class="card-body">
                                        @if($images->count() > 0)
                                            <div class="row mb-3">
                                                @foreach($images as $image)
                                                    <div class="col-md-3 mb-3">
                                                        <img src="{{ asset($image->url) }}" class="img-fluid rounded" alt="Car Image">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="dz-message needsclick">
                                            Drop files here or click to upload new images
                                            <span class="note needsclick">
                                                (This will replace all existing images)
                                            </span>
                                        </div>
                                        <div class="fallback">
                                            <input name="images[]" type="file" multiple />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('images')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('cars.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
