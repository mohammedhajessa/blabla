{{--  @extends('partial.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Passenger Dashboard</h4>

        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="fw-medium d-block mb-1">Total Rides</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2"></h4>
                                    <span class="badge bg-label-success">+8%</span>
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
                                <span class="fw-medium d-block mb-1">Spending</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2"></h4>
                                    <span class="badge bg-label-info">+3%</span>
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
                                <span class="fw-medium d-block mb-1">Favorite Routes</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2"></h4>
                                    <span class="badge bg-label-warning">
                                        <i class="ti ti-map-pin ti-sm"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-map ti-sm"></i>
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
                                <span class="fw-medium d-block mb-1">Account Status</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">Active</h4>
                                    <span class="badge bg-label-success">
                                        <i class="ti ti-check ti-sm"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-circle-check ti-sm"></i>
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
                        <h5 class="card-title mb-0">Recent Rides</h5>
                        <a href="#" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ride ID</th>
                                        <th>Date</th>
                                        <th>Fare</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($recentRides) && count($recentRides) > 0)
                                        @foreach($recentRides as $ride)
                                        <tr>
                                            <td>{{ $ride->id }}</td>
                                            <td>{{ $ride->date }}</td>
                                            <td>${{ $ride->fare }}</td>
                                            <td><span class="badge bg-label-{{ $ride->status == 'Completed' ? 'success' : 'warning' }}">{{ $ride->status }}</span></td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No recent rides found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">My Profile</h5>
                        <a href="{{ route('passenger.profile') }}" class="btn btn-sm btn-primary">View Profile</a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg me-3">
                                @if(Auth::guard('passenger')->user()->profile && Auth::guard('passenger')->user()->profile->profile_picture)
                                    <img src="{{ Auth::guard('passenger')->user()->profile->profile_picture }}" alt="Profile Picture" class="rounded">
                                @else
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ti ti-user ti-md"></i>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-0">{{ Auth::guard('passenger')->user()->name }}</h5>
                                <span class="badge bg-label-info">{{ Auth::guard('passenger')->user()->email }}</span>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Phone</small>
                                <div class="fw-medium">{{ Auth::guard('passenger')->user()->phone ?? 'Not set' }}</div>
                            </div>
                            @if(Auth::guard('passenger')->user()->profile)
                                <div class="col-6">
                                    <small class="text-muted d-block">Gender</small>
                                    <div class="fw-medium text-capitalize">{{ Auth::guard('passenger')->user()->profile->gender ?? 'Not set' }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Age</small>
                                    <div class="fw-medium">{{ Auth::guard('passenger')->user()->profile->age ?? 'Not set' }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Address</small>
                                    <div class="fw-medium">{{ Auth::guard('passenger')->user()->profile->address ?? 'Not set' }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Upcoming Rides</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @if(isset($upcomingRides) && count($upcomingRides) > 0)
                                @foreach($upcomingRides as $ride)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-0">{{ $ride->pickup_location }} to {{ $ride->dropoff_location }}</h6>
                                        <small class="text-muted">{{ $ride->date }} at {{ $ride->time }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $ride->distance }} km</span>
                                </li>
                                @endforeach
                            @else
                                <li class="list-group-item text-center">No upcoming rides</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('passenger.profile') }}" class="btn btn-outline-primary d-grid">
                                    <i class="ti ti-user me-1"></i> Edit Profile
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-success d-grid">
                                    <i class="ti ti-map-pin me-1"></i> Book a Ride
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-info d-grid">
                                    <i class="ti ti-history me-1"></i> Ride History
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-warning d-grid">
                                    <i class="ti ti-help me-1"></i> Get Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection  --}}
