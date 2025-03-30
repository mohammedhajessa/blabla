@extends('partial.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span></h4>
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span></h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center">
                        <div class="position-relative mb-4">
                            @if($passengerProfile && $passengerProfile->profile_picture)
                                <a href="{{ asset($passengerProfile->profile_picture) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($passengerProfile->profile_picture) }}" data-img-title="Profile Image">
                                    <img src="{{ asset($passengerProfile->profile_picture) }}" alt="Profile Image" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                                </a>
                            @else
                                <div class="rounded-circle bg-label-primary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; border: 4px solid #fff;">
                                    <span style="font-size: 2.5rem;">{{ substr($passenger->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <h5 class="card-title mb-1 fw-semibold">{{ $passenger->name }}</h5>
                        <div class="text-muted mb-2">{{ $passenger->email }}</div>
                        <p class="text-muted mb-3">
                        <span class="badge bg-label-primary">Passenger</span>
                        @if($passenger->phone)
                            <span class="ms-2"><i class="ti ti-phone me-1"></i>{{ $passenger->phone }}</span>
                        @endif
                        </p>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('passenger.editProfile') }}" class="btn btn-primary waves-effect waves-light">
                            <i class="ti ti-edit me-1"></i> Edit Profile
                        </a>
                        <a href="{{ route('passenger.journeyRequests') }}" class="btn btn-outline-primary waves-effect">
                            <i class="ti ti-car me-1"></i> My Journeys
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <h6 class="mb-0 fw-semibold">{{ $journeyPassengers->count() }}</h6>
                            <small>Total Rides</small>
                        </div>
                        <div class="text-center">
                            <h6 class="mb-0 fw-semibold">{{ $journeyPassengers->where('status', 'completed')->count() }}</h6>
                            <small>Completed</small>
                        </div>
                        <div class="text-center">
                            <h6 class="mb-0 fw-semibold">{{ $journeyPassengers->where('status', 'pending')->count() }}</h6>
                            <small>Pending</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Journey Card -->
            @if($currentJourney)
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center bg-primary bg-opacity-10">
                    <h5 class="card-title mb-0"><i class="ti ti-route me-2 text-primary"></i>Current Journey</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-primary me-2"></i>
                        <div>
                            <small class="text-muted">From</small>
                            <div class="fw-semibold">{{ $currentJourney->journey->from_city }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin-filled text-primary me-2"></i>
                        <div>
                            <small class="text-muted">To</small>
                            <div class="fw-semibold">{{ $currentJourney->journey->to_city }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-calendar text-primary me-2"></i>
                        <div>
                            <small class="text-muted">Date</small>
                            <div class="fw-semibold">{{ $currentJourney->journey->date }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Journeys Card -->
            @if($recentJourneys)
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="ti ti-history me-2 text-primary"></i>Recent Journey</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-primary me-2"></i>
                        <div>
                            <small class="text-muted">From</small>
                            <div class="fw-semibold">{{ $recentJourneys->journey->from_city }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin-filled text-primary me-2"></i>
                        <div>
                            <small class="text-muted">To</small>
                            <div class="fw-semibold">{{ $recentJourneys->journey->to_city }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-calendar text-primary me-2"></i>
                        <div>
                            <small class="text-muted">Date</small>
                            <div class="fw-semibold">{{ $recentJourneys->journey->date }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0"><i class="ti ti-link me-2 text-primary"></i>Quick Links</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center px-0">
                            <i class="ti ti-car me-2 text-primary"></i>
                            <a href="{{ route('passenger.journeyRequests') }}" class="text-body">My Journeys</a>
                        </li>
                        <li class="list-group-item d-flex align-items-center px-0">
                            <i class="ti ti-settings me-2 text-primary"></i>
                            <a href="#" class="text-body">Account Settings</a>
                        </li>
                        <li class="list-group-item d-flex align-items-center px-0">
                            <i class="ti ti-shield-lock me-2 text-primary"></i>
                            <a href="#" class="text-body">Security</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-user-circle me-2 text-primary"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-user me-1"></i> Full Name</h6>
                                <p class="mb-0">{{ $passenger->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-mail me-1"></i> Email</h6>
                                <p class="mb-0">{{ $passenger->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-phone me-1"></i> Phone Number</h6>
                                <p class="mb-0">{{ $passenger->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-gender-bigender me-1"></i> Gender</h6>
                                <p class="mb-0">
                                    @if($passengerProfile && $passengerProfile->gender)
                                        {{ ucfirst($passengerProfile->gender) }}
                                        <i class="ti ti-{{ $passengerProfile->gender == 'male' ? 'man' : 'woman' }}"></i>
                                    @else
                                        Not provided
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-calendar me-1"></i> Age</h6>
                                <p class="mb-0">{{ $passengerProfile && $passengerProfile->age ? $passengerProfile->age : 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-3"><i class="ti ti-map-pin me-1 text-primary"></i> Location Information</h6>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2">Address</h6>
                                <p class="mb-0">{{ $passengerProfile && $passengerProfile->address ? $passengerProfile->address : 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2">City</h6>
                                <p class="mb-0">{{ $passengerProfile && $passengerProfile->city ? $passengerProfile->city : 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light-subtle">
                                <h6 class="fw-semibold text-primary mb-2">Region</h6>
                                <p class="mb-0">{{ $passengerProfile && $passengerProfile->region ? $passengerProfile->region : 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-id me-2 text-primary"></i>Identification Documents</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border">
                                <div class="card-header bg-transparent">
                                    <h6 class="fw-semibold mb-0">Identification Front</h6>
                                </div>
                                <div class="card-body text-center p-3">
                                    @if($passengerProfile && $passengerProfile->identification_front_image)
                                        <a href="{{ asset($passengerProfile->identification_front_image) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($passengerProfile->identification_front_image) }}" data-img-title="Identification Front Image">
                                            <img src="{{ asset($passengerProfile->identification_front_image) }}"
                                                class="img-fluid rounded shadow-sm" alt="Identification Front" style="max-height: 180px;">
                                        </a>
                                    @else
                                        <div class="p-5 border rounded bg-light-subtle d-flex align-items-center justify-content-center" style="height: 180px;">
                                            <span class="text-muted"><i class="ti ti-photo ti-lg me-2"></i>No image uploaded</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-none border">
                                <div class="card-header bg-transparent">
                                    <h6 class="fw-semibold mb-0">Identification Back</h6>
                                </div>
                                <div class="card-body text-center p-3">
                                    @if($passengerProfile && $passengerProfile->identification_back_image)
                                        <a href="{{ asset($passengerProfile->identification_back_image) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($passengerProfile->identification_back_image) }}" data-img-title="Identification Back Image">
                                            <img src="{{ asset($passengerProfile->identification_back_image) }}"
                                                class="img-fluid rounded shadow-sm" alt="Identification Back" style="max-height: 180px;">
                                        </a>
                                    @else
                                        <div class="p-5 border rounded bg-light-subtle d-flex align-items-center justify-content-center" style="height: 180px;">
                                            <span class="text-muted"><i class="ti ti-photo ti-lg me-2"></i>No image uploaded</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Preview">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageModal = document.getElementById('imageModal');
            if (imageModal) {
                imageModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const imgSrc = button.getAttribute('data-img-src');
                    const imgTitle = button.getAttribute('data-img-title');

                    const modalImage = document.getElementById('modalImage');
                    const modalTitle = imageModal.querySelector('.modal-title');

                    modalImage.src = imgSrc;
                    modalTitle.textContent = imgTitle;
                });
            }

            // Auto dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</div>
@endsection
