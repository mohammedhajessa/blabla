@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Journeys /</span> Create New Journey
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Journey Details</h5>
                    <a href="{{ route('journeys.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('journeys.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="driver_id" value="{{ Auth::guard('driver')->user()->id }}">

                        <!-- Cities Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="pickup_city_id" class="form-label">Pickup City</label>
                                <select id="pickup_city_id" name="pickup_city_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('pickup_city_id') == $city->id ? 'selected' : '' }}>
                                            {{ strtoupper(ucfirst($city->name)) }}
                                            @if($city->region)
                                                ({{ strtoupper(ucfirst($city->region->name)) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('pickup_city_id')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dropoff_city_id" class="form-label">Dropoff City</label>
                                <select id="dropoff_city_id" name="dropoff_city_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('dropoff_city_id') == $city->id ? 'selected' : '' }}>
                                            {{ strtoupper(ucfirst($city->name)) }}
                                            @if($city->region)
                                                ({{ strtoupper(ucfirst($city->region->name)) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('dropoff_city_id')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Addresses -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="pickup_address">Pickup Address</label>
                                <input type="text" class="form-control @error('pickup_address') is-invalid @enderror" id="pickup_address" name="pickup_address" value="{{ old('pickup_address') }}">
                                @error('pickup_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="dropoff_address">Dropoff Address</label>
                                <input type="text" class="form-control @error('dropoff_address') is-invalid @enderror" id="dropoff_address" name="dropoff_address" value="{{ old('dropoff_address') }}">
                                @error('dropoff_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Times and Date -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="journey_date">Journey Date</label>
                                <input type="date" class="form-control @error('journey_date') is-invalid @enderror" id="journey_date" name="journey_date" min="{{ now()->format('Y-m-d') }}" value="{{ old('journey_date') }}" required>
                                @error('journey_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="pickup_time">Pickup Time</label>
                                <input type="datetime-local" class="form-control @error('pickup_time') is-invalid @enderror" id="pickup_time" name="pickup_time"  value="{{ old('pickup_time') }}" required>
                                @error('pickup_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="arrival_time">Arrival Time</label>
                                <input type="datetime-local" class="form-control @error('arrival_time') is-invalid @enderror" id="arrival_time" name="arrival_time" min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('arrival_time') }}" required>
                                @error('arrival_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Journey Details -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="price">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="available_seats">Available Seats</label>
                                <input type="number" class="form-control @error('available_seats') is-invalid @enderror" id="available_seats" name="available_seats" value="{{ old('available_seats') }}" required>
                                @error('available_seats')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="distance">Distance (km)</label>
                                <input type="number" class="form-control @error('distance') is-invalid @enderror" id="distance" name="distance" step="0.01" value="{{ old('distance') }}" required>
                                @error('distance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="status" value="pending">

                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Create Journey
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
