@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Journeys /</span> Journey Details
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Journey Information</h5>
            <div>
                @if($journey->status == 'pending')
                    <a href="{{ route('journeys.edit', $journey->id) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit me-1"></i>Edit Journey
                    </a>
                @endif
                <a href="{{ route('journeys.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Route Details</h6>
                    <div class="mb-3">
                        <label class="form-label">Pickup City</label>
                        <p class="form-control-static">{{ $journey->pickupCity->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dropoff City</label>
                        <p class="form-control-static">{{ $journey->dropoffCity->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pickup Address</label>
                        <p class="form-control-static">{{ $journey->pickup_address }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dropoff Address</label>
                        <p class="form-control-static">{{ $journey->dropoff_address }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Journey Details</h6>
                    <div class="mb-3">
                        <label class="form-label">Journey Date</label>
                        <p class="form-control-static">{{ $journey->journey_date }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pickup Time</label>
                        <p class="form-control-static">{{ $journey->pickup_time }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Arrival Time</label>
                        <p class="form-control-static">{{ $journey->arrival_time }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <p class="form-control-static">
                            <span class="badge bg-label-{{ $journey->status == 'completed' ? 'success' : ($journey->status == 'pending' ? 'warning' : 'info') }}">
                                {{ ucfirst($journey->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Journey Statistics</h6>
                    <div class="mb-3">
                        <label class="form-label">Distance</label>
                        <p class="form-control-static">{{ $journey->distance }} km</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Available Seats</label>
                        <p class="form-control-static">{{ $journey->available_seats }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <p class="form-control-static">${{ number_format($journey->price, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Passengers</label>
                        <p class="form-control-static">{{ App\Models\JourneyRequest::where('journey_id', $journey->id)->count() }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Driver Information</h6>
                    <div class="mb-3">
                        <label class="form-label">Driver Name</label>
                        <p class="form-control-static">{{ $journey->driver->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <p class="form-control-static">
                            <i class="bx bx-phone me-1"></i>
                            <a href="https://chat.whatsapp.com/{{ $journey->driver->driverProfile->phone }}">
                                {{ $journey->driver->driverProfile->phone }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection