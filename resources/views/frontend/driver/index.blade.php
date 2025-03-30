@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Total Drivers</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $drivers->count() }}</h4>
                                </div>
                                <small class="mb-0">All registered drivers</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-user ti-26px"></i>
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
                                <span class="text-heading">Active Drivers</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $drivers->where('status', 'active')->count() }}</h4>
                                </div>
                                <small class="mb-0">Currently active drivers</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-user-check ti-26px"></i>
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
                                <span class="text-heading">Pending Approval</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $drivers->where('registration_status', 'pending')->count() }}
                                    </h4>
                                </div>
                                <small class="mb-0">Awaiting verification</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-clock ti-26px"></i>
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
                                <span class="text-heading">New Drivers</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">
                                        {{ $drivers->where('created_at', '>=', now()->subMonth())->count() }}</h4>
                                </div>
                                <small class="mb-0">Joined this month</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-user-plus ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Drivers List Table -->
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Drivers Management</h5>
              
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-drivers table">
                    <thead class="border-top">
                        <tr>
                            <th>ID</th>
                            <th>Driver Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Registration Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($driver->name) }}</td>
                                <td>{{ $driver->email }}</td>
                                <td>{{ $driver->driverProfile->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $driver->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($driver->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('driver.updateStatusRegister', $driver->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm registration-status-select"
                                            data-driver-id="{{ $driver->id }}"
                                            data-url="{{ route('driver.updateStatusRegister', $driver->id) }}"
                                            onchange="this.form.submit()"
                                            style="min-width: 130px;
                                                background-color: {{ $driver->registration_status == 'approved' ? '#E7F6E7' : ($driver->registration_status == 'pending' ? '#FFF4E5' : '#FFE7E5') }};
                                                border-width: 2px;
                                                border-style: solid;
                                                border-color: {{ $driver->registration_status == 'approved' ? '#71DD37' : ($driver->registration_status == 'pending' ? '#FFAB00' : '#FF3E1D') }};
                                                color: {{ $driver->registration_status == 'approved' ? '#2B7424' : ($driver->registration_status == 'pending' ? '#B76E00' : '#B71D1D') }};
                                                font-weight: 500;
                                                border-radius: 6px;
                                                padding: 0.3rem 0.5rem;">
                                            <option value="approved"
                                                {{ $driver->registration_status == 'approved' ? 'selected' : '' }}
                                                style="background-color: #E7F6E7; color: #2B7424">
                                                Approved
                                            </option>
                                            <option value="pending"
                                                {{ $driver->registration_status == 'pending' ? 'selected' : '' }}
                                                style="background-color: #FFF4E5; color: #B76E00">
                                                Pending
                                            </option>
                                            <option value="rejected"
                                                {{ $driver->registration_status == 'rejected' ? 'selected' : '' }}
                                                style="background-color: #FFE7E5; color: #B71D1D">
                                                Rejected
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $driver->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-icon btn-sm btn-text-secondary rounded-pill show-confirm"
                                                onclick="return confirm('Are you sure you want to delete this driver?')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No drivers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
