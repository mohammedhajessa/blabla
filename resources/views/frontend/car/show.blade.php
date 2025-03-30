@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Car Status</span>
                                <div class="d-flex align-items-center my-1">
                                    <span class="badge bg-label-{{ $car->status == 'active' ? 'success' : 'danger' }} fs-5">
                                        {{ ucfirst($car->status) }}
                                    </span>
                                </div>
                                <small class="mb-0">Current car status</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-car ti-26px"></i>
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
                                <span class="text-heading">Fuel Type</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ ucfirst($car->fuel_type) }}</h4>
                                </div>
                                <small class="mb-0">Car fuel type</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-gas-station ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Car Details</h5>
                <div>
                    <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Car Name</label>
                        <p class="form-control-static fw-medium">{{ $car->name }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">License Plate</label>
                        <p class="form-control-static fw-medium">{{ $car->no_plat }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year</label>
                        <p class="form-control-static fw-medium">{{ $car->year }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Number of Seats</label>
                        <p class="form-control-static fw-medium">{{ $car->no_seats }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fuel Type</label>
                        <p class="form-control-static fw-medium">{{ ucfirst($car->fuel_type) }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fuel Capacity</label>
                        <p class="form-control-static fw-medium">{{ $car->fuel_capacity }} Liters</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Transmission</label>
                        <p class="form-control-static fw-medium">{{ ucfirst($car->transmission) }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Color</label>
                        <p class="form-control-static fw-medium">{{ ucfirst($car->color) }}</p>
                    </div>
                    @if($car->note)
                    <div class="col-12 mb-3">
                        <label class="form-label">Notes</label>
                        <p class="form-control-static fw-medium">{{ $car->note }}</p>
                    </div>
                    @endif

                    <div class="col-12">
                        <hr class="my-4">
                        <h6 class="mb-3">Vehicle Documentation</h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Registration Number</label>
                        <p class="form-control-static fw-medium">{{ $car->registration_number }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Registration Expiry</label>
                        <p class="form-control-static fw-medium">{{ $car->registration_expiry }}</p>
                    </div>

                    <div class="col-12">
                        <hr class="my-4">
                        <h6 class="mb-3">Car Images</h6>
                    </div>

                    <div class="col-12">
                        @if($car->images && count($car->images) > 0)
                            <div class="row g-3">
                                @foreach($car->images as $image)
                                <div class="col-6">
                                    <a href="{{ asset($image->url) }}" data-lightbox="car-gallery" data-title="Car Image">
                                        <img src="{{ asset($image->url) }}" alt="Car Image" class="img-fluid rounded">
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center py-3">No images available</p>
                        @endif
                    </div>

                    <div class="col-12">
                        <hr class="my-4">
                        <h6 class="mb-3">Driver Information</h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Driver Name</label>
                        <p class="form-control-static fw-medium">{{ $car->driver->name }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Driver Phone</label>
                        <p class="form-control-static fw-medium">{{ $car->driver->driverProfile->phone ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Driver Email</label>
                        <p class="form-control-static fw-medium">{{ $car->driver->email }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Driver Status</label>
                        <p class="form-control-static">
                            <span class="badge bg-label-{{ $car->driver->status == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($car->driver->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
