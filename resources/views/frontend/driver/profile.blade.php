@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Driver Profile</h4>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mb-3">
                        @if($driver->driverProfile->image)
                            <a href="{{ asset($driver->driverProfile->image) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($driver->driverProfile->image) }}" data-img-title="Profile Image">
                                <img src="{{ asset($driver->driverProfile->image) }}" alt="Profile Image" class="rounded-circle" style="width: 100px; height: 100px;">
                            </a>
                        @else
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                {{ substr($driver->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <h5 class="card-title mb-1">{{ $driver->name }}</h5>
                    <p class="text-muted">Driver</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('driver.editProfile') }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Status Information</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-circle-check text-success me-2"></i>
                            <span>Status: <span class="badge bg-label-{{ $driver->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($driver->status) }}</span></span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-clipboard-check text-primary me-2"></i>
                            <span>Registration: <span class="badge bg-label-{{ $driver->registration_status === 'approved' ? 'success' : 'danger' }}">{{ ucfirst($driver->registration_status) }}</span></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Personal Information</h5>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Full Name</h6>
                            <p>{{ $driver->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Email</h6>
                            <p>{{ $driver->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Phone Number</h6>
                            <p>{{ $driver->driverProfile->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Gender</h6>
                            <p>{{ ucfirst($driver->driverProfile->gender ?? 'Not provided') }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h6 class="fw-semibold">Address</h6>
                            <p>{{ $driver->driverProfile->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <h5 class="card-header">License Information</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">License Number</h6>
                            <p>{{ $driver->driverProfile->license_number ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">License Expiry Date</h6>
                            <p>{{ $driver->driverProfile->license_expiry_date ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Identity Number</h6>
                            <p>{{ $driver->driverProfile->identity_number ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">License Front Image</h6>
                            @if(isset($driver->driverProfile->license_front_image))
                                <a href="{{ asset($driver->driverProfile->license_front_image) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($driver->driverProfile->license_front_image) }}" data-img-title="License Front Image">
                                    <img src="{{ asset($driver->driverProfile->license_front_image) }}"
                                         class="img-fluid rounded" alt="License Front" style="max-height: 200px;">
                                </a>
                            @else
                                <p>No image uploaded</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">License Back Image</h6>
                            @if(isset($driver->driverProfile->license_back_image))
                                <a href="{{ asset($driver->driverProfile->license_back_image) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($driver->driverProfile->license_back_image) }}" data-img-title="License Back Image">
                                    <img src="{{ asset($driver->driverProfile->license_back_image) }}"
                                         class="img-fluid rounded" alt="License Back" style="max-height: 200px;">
                                </a>
                            @else
                                <p>No image uploaded</p>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Identity Front Image</h6>
                            @if(isset($driver->driverProfile->identity_front_image))
                                <a href="{{ asset($driver->driverProfile->identity_front_image) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($driver->driverProfile->identity_front_image) }}" data-img-title="Identity Front Image">
                                    <img src="{{ asset($driver->driverProfile->identity_front_image) }}"
                                         class="img-fluid rounded" alt="Identity Front" style="max-height: 200px;">
                                </a>
                            @else
                                <p>No image uploaded</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Identity Back Image</h6>
                            @if(isset($driver->driverProfile->identity_back_image))
                                <a href="{{ asset($driver->driverProfile->identity_back_image) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ asset($driver->driverProfile->identity_back_image) }}" data-img-title="Identity Back Image">
                                    <img src="{{ asset($driver->driverProfile->identity_back_image) }}"
                                         class="img-fluid rounded" alt="Identity Back" style="max-height: 200px;">
                                </a>
                            @else
                                <p>No image uploaded</p>
                            @endif
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
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
