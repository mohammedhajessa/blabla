@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Edit Profile</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('driver.updateProfile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $driver->name }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $driver->email }}" >
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-muted">(optional)</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank if you don't want to change it">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $driver->driverProfile->phone ?? '' }}" required>
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $driver->driverProfile->address ?? '' }}" required>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="male" {{ isset($driver->driverProfile) && $driver->driverProfile->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ isset($driver->driverProfile) && $driver->driverProfile->gender == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ $driver->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $driver->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">License Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="license_number" class="form-label">License Number</label>
                                <input type="text" class="form-control" id="license_number" name="license_number" value="{{ $driver->driverProfile->license_number ?? '' }}">
                                @error('license_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="license_expiry_date" class="form-label">License Expiry Date</label>
                                <input type="date" class="form-control" id="license_expiry_date" name="license_expiry_date" value="{{ $driver->driverProfile->license_expiry_date ?? '' }}">
                                @error('license_expiry_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="license_front_image" class="form-label">License Front Image</label>
                                <input type="file" class="form-control" id="license_front_image" name="license_front_image">
                                @error('license_front_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="license_back_image" class="form-label">License Back Image</label>
                                <input type="file" class="form-control" id="license_back_image" name="license_back_image">
                                @error('license_back_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Identity Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="identity_number" class="form-label">Identity Number</label>
                                <input type="text" class="form-control" id="identity_number" name="identity_number" value="{{ $driver->driverProfile->identity_number ?? '' }}">
                                @error('identity_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="identity_front_image" class="form-label">Identity Front Image</label>
                                <input type="file" class="form-control" id="identity_front_image" name="identity_front_image">
                                @error('identity_front_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="identity_back_image" class="form-label">Identity Back Image</label>
                                <input type="file" class="form-control" id="identity_back_image" name="identity_back_image">
                                @error('identity_back_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>


                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                            <a href="{{ route('driver.profile') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection