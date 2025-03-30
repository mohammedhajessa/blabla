@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Driver Dashboard</h4>

        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Total Journeys</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $totalTrips ?? 0 }}</h4>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-car ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Earnings</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">${{ $totalEarnings ?? '0.00' }}</h4>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-currency-dollar ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Rating</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ number_format($avarageRating) }}</h4>
                                    <span class="badge bg-label-warning">
                                        <i class="ti ti-star ti-sm"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-star-filled ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Status</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $driver->status ?? 'active' }}</h4>
                                    <span class="badge bg-label-{{ $driver->status == 'active' ? 'success' : 'danger' }}">
                                        <i class="ti ti-{{ $driver->status == 'active' ? 'check' : 'x' }} ti-sm"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-{{ $driver->status == 'active' ? 'success' : 'danger' }}">
                                    <i class="ti ti-{{ $driver->status == 'active' ? 'circle-check' : 'circle-x' }} ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Trips</h5>
                        <a href="{{ route('journeys.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Trip ID</th>
                                        <th>Date</th>
                                        <th>Fare</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($recentTrips) && count($recentTrips) > 0)
                                        @foreach($recentTrips as $trip)
                                        <tr>
                                            <td>{{ $trip->id }}</td>
                                            <td>{{ $trip->journey_date }}</td>
                                            <td>${{ number_format($trip->price, 2) }}</td>
                                            <td><span class="badge bg-label-{{ $trip->status == 'completed' ? 'success' : 'warning' }}">{{ $trip->status }}</span></td>
                                            <td>
                                                <a href="{{ route('journeys.show', $trip->id) }}" class="btn btn-sm btn-icon btn-outline-primary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No recent trips found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if(isset($recentTrips) && count($recentTrips) > 0)
                            <div class="d-flex justify-content-center mt-3">
                                {{ $recentTrips->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Last Journey</h5>
                        <a href="{{ route('journeys.show', $lastJourney->id ?? 0) }}" class="btn btn-sm btn-primary">View</a>
                    </div>
                    <div class="card-body">
                        @if(isset($lastJourney) && $lastJourney)
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $lastJourney->pickup_location }} <i class="ti ti-arrow-right mx-2"></i> {{ $lastJourney->dropoff_location }}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted"><i class="ti ti-calendar me-1"></i>{{ $lastJourney->journey_date }}</small>
                                        <small class="text-muted"><i class="ti ti-clock me-1"></i>{{ $lastJourney->pickup_time }}</small>
                                        <small class="text-muted"><i class="ti ti-ruler me-1"></i>{{ $lastJourney->distance }} km</small>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0">${{ number_format($lastJourney->price, 2) }}</h5>
                                </div>
                            </div>
                            <div class="border-top pt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Passenger</small>
                                        {{--  <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <img src="{{ $lastJourney->passenger->avatar ?? asset('assets/img/avatars/default.png') }}" alt="Avatar" class="rounded-circle">
                                            </div>
                                            <span>{{ $lastJourney->passenger->name }}</span>
                                        </div>  --}}
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block">Rating</small>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ti ti-star{{ $i <= ($avarageRating ?? 0) ? '-filled' : '' }} ti-sm"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <div class="mb-3">
                                    <i class="ti ti-map-off ti-lg text-muted"></i>
                                </div>
                                <h6>No journey completed yet</h6>
                                <p class="text-muted">Your last completed journey will appear here</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">My Car</h5>
                        <a href="{{ route('driver.cars') }}" class="btn btn-sm btn-primary">Manage Car</a>
                    </div>
                    <div class="card-body">
                        @if(isset($car) && $car)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-lg me-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ti ti-car ti-md"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $car->name }}</h5>
                                    <span class="badge bg-label-info">{{ $car->no_plat }}</span>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Year</small>
                                    <div class="fw-medium">{{ $car->year }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Seats</small>
                                    <div class="fw-medium">{{ $car->no_seats }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Fuel Type</small>
                                    <div class="fw-medium text-capitalize">{{ $car->fuel_type }}</div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <div class="mb-3">
                                    <i class="ti ti-car-off ti-lg text-muted"></i>
                                </div>
                                <h6>No car registered yet</h6>
                                <p class="mb-3">Register your car to start accepting rides</p>
                                <a href="{{ route('driver.createCar') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i> Add Car
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
{{--
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Upcoming Schedule</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @if(isset($upcomingSchedules) && count($upcomingSchedules) > 0)
                                @foreach($upcomingSchedules as $schedule)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-0">{{ $schedule->pickup_location }} to {{ $schedule->dropoff_location }}</h6>
                                        <small class="text-muted">{{ $schedule->journey_date }} at {{ $schedule->pickup_time }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $schedule->distance }} km</span>
                                </li>
                                @endforeach
                            @else
                                <li class="list-group-item text-center">No upcoming schedules</li>
                            @endif
                        </ul>
                    </div>
                </div>  --}}

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col">
                                <a href="{{ route('driver.profile') }}" class="btn btn-outline-primary d-grid">
                                    <i class="ti ti-user me-1"></i> View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
