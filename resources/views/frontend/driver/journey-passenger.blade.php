@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Driver /</span> Passenger Details</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left column - Passenger Profile Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <!-- Profile Picture -->
                    <div class="d-flex justify-content-center">
                        <div class="position-relative mb-4">
                            @if($passenger->passengerProfile && $passenger->passengerProfile->profile_picture)
                                <img src="{{ asset($passenger->passengerProfile->profile_picture) }}" alt="Profile Image" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                            @else
                                <div class="rounded-circle bg-label-primary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; border: 4px solid #fff;">
                                    <span style="font-size: 2.5rem;">{{ substr($passenger->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Passenger Details -->
                    <div class="text-center mt-2">
                        <h5 class="card-title mb-1 fw-semibold">{{ $passenger->name }}</h5>
                        <div class="text-muted mb-2">{{ $passenger->email }}</div>
                        <p class="text-muted mb-3">
                            <span class="badge bg-label-primary">Passenger</span>
                            @if($passenger->phone)
                                <span class="ms-2"><i class="bx bx-phone me-1"></i>{{ $passenger->phone }}</span>
                            @endif
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-2">
                        <a href="https://wa.me/{{ $passenger->phone }}" class="btn btn-primary waves-effect waves-light">
                            <i class="bx bx-message-square-dots me-1"></i> Contact
                        </a>
                        <a href="{{ route('driver.journeyPassengers') }}" class="btn btn-outline-secondary waves-effect">
                            <i class="bx bx-arrow-back me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right column - Passenger Information and Journey History -->
        <div class="col-md-8">
            <!-- Passenger Information Card -->
            <div class="card mb-4">
                <h5 class="card-header">Passenger Information</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Contact Information</h6>
                            <p class="mb-1"><i class="bx bx-envelope me-1 text-muted"></i> {{ $passenger->email }}</p>
                            <p class="mb-1"><i class="bx bx-phone me-1 text-muted"></i> {{ $passenger->phone }}</p>
                            @if($passenger->passengerProfile && $passenger->passengerProfile->address)
                                <p class="mb-1"><i class="bx bx-map me-1 text-muted"></i> {{ $passenger->passengerProfile->address }}</p>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Personal Details</h6>
                            @if($passenger->passengerProfile)
                                @if($passenger->passengerProfile->gender)
                                    <p class="mb-1"><i class="bx bx-user me-1 text-muted"></i> {{ ucfirst($passenger->gender) }}</p>
                                @endif
                                @if($passenger->age)
                                    <p class="mb-1"><i class="bx bx-calendar me-1 text-muted"></i> {{ $passenger->age }} years old</p>
                                @endif
                                @if($passenger->city)
                                    <p class="mb-1"><i class="bx bx-building me-1 text-muted"></i> {{ $passenger->city }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Journey History Card -->
            <div class="card mb-4">
                <h5 class="card-header">Journey History</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Route</th>
                                <th>Pickup Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($passenger->journeyPassengers as $journeyPassenger)
                            <tr>
                                <td>{{ $journeyPassenger->journey->journey_date }}</td>
                                <td>
                                    <span class="badge bg-label-info">
                                        {{ $journeyPassenger->journey->pickupCity->name ?? 'Unknown' }} to {{ $journeyPassenger->journey->dropoffCity->name ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>{{ $journeyPassenger->journey->pickup_time }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No journey history found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection