@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Journeys /</span> Edit Journey
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
                    <form action="{{ route('journeys.update', $journey->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="driver_id" value="{{ Auth::guard('driver')->user()->id }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="pickup_city_id">Pickup City</label>
                                <select id="pickup_city_id" name="pickup_city_id" class="form-select @error('pickup_city_id') is-invalid @enderror" required>
                                    <option value="">Select Pickup City</option>
                                    @foreach($cities ?? [] as $city)
                                        <option value="{{ $city->id }}" {{ $journey->pickup_city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('pickup_city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="dropoff_city_id">Dropoff City</label>
                                <select id="dropoff_city_id" name="dropoff_city_id" class="form-select @error('dropoff_city_id') is-invalid @enderror" required>
                                    <option value="">Select Dropoff City</option>
                                    @foreach($cities ?? [] as $city)
                                        <option value="{{ $city->id }}" {{ $journey->dropoff_city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('dropoff_city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="pickup_time">Pickup Time</label>
                                <input type="datetime-local" class="form-control @error('pickup_time') is-invalid @enderror" id="pickup_time" name="pickup_time" value="{{ $journey->pickup_time }}" required>
                                @error('pickup_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="arrival_time">Arrival Time</label>
                                <input type="datetime-local" class="form-control @error('arrival_time') is-invalid @enderror" id="arrival_time" name="arrival_time" value="{{ $journey->arrival_time }}" required>
                                @error('arrival_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="price">Price</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01" value="{{ $journey->price }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="available_seats">Available Seats</label>
                                <input type="number" class="form-control @error('available_seats') is-invalid @enderror" id="available_seats" name="available_seats" value="{{ $journey->available_seats }}" required>
                                @error('available_seats')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="distance">Distance</label>
                                <input type="number" class="form-control @error('distance') is-invalid @enderror" id="distance" name="distance" step="0.01" value="{{ $journey->distance }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="status">Status</label>
                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ $journey->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $journey->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ $journey->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $journey->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="pickup_address">Pickup Address</label>
                                <input type="text" class="form-control @error('pickup_address') is-invalid @enderror" id="pickup_address" name="pickup_address" value="{{ $journey->pickup_address }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="dropoff_address">Dropoff Address</label>
                                <input type="text" class="form-control @error('dropoff_address') is-invalid @enderror" id="dropoff_address" name="dropoff_address" value="{{ $journey->dropoff_address }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> Update Journey
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
