@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            @if($car)
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
            @endif
        </div>

        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Car Details</h5>
                @if(!$car)
                    <a href="{{ route('driver.createCar') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Register New Car
                    </a>
                @endif
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($car)
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
                            <label class="form-label">Status</label>
                            <p class="form-control-static">
                                <span class="badge bg-label-{{ $car->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($car->status) }}
                                </span>
                            </p>
                        </div>

                        @if($car->note)
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <p class="form-control-static fw-medium">{{ $car->note }}</p>
                        </div>
                        @endif

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
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-primary me-2">
                                <i class="ti ti-edit me-1"></i> Edit Car
                            </a>
                            <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this car?')">
                                    <i class="ti ti-trash me-1"></i> Delete Car
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-car-off ti-lg text-muted"></i>
                        </div>
                        <h6>No car registered yet</h6>
                        <p class="mb-3">Register your car to start accepting rides</p>
                        <a href="{{ route('driver.createCar') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Register New Car
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
