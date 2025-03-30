@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Total Passengers</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $passengers->count() }}</h4>
                                    <p class="text-success mb-0">(+15%)</p>
                                </div>
                                <small class="mb-0">All registered passengers</small>
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
                                <span class="text-heading">Active Passengers</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $passengers->where('status', 'active')->count() }}</h4>
                                    <p class="text-success mb-0">(+10%)</p>
                                </div>
                                <small class="mb-0">Currently active passengers</small>
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
                                <span class="text-heading">Verified Passengers</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $passengers->where('is_verified', true)->count() }}</h4>
                                    <p class="text-success mb-0">(+8%)</p>
                                </div>
                                <small class="mb-0">Identity verified</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-shield-check ti-26px"></i>
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
                                <span class="text-heading">New Passengers</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">
                                        {{ $passengers->where('created_at', '>=', now()->subMonth())->count() }}</h4>
                                    <p class="text-success mb-0">(+12%)</p>
                                </div>
                                <small class="mb-0">Joined this month</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-user-plus ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Passengers List Table -->
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Passengers Management</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-passengers table">
                    <thead class="border-top">
                        <tr>
                            <th>ID</th>
                            <th>Passenger Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Verified</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($passengers as $passenger)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($passenger->name) }}</td>
                                <td>{{ $passenger->email }}</td>
                                <td>{{ $passenger->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $passenger->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($passenger->status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $passenger->is_verified ? 'success' : 'warning' }}">
                                        {{ $passenger->is_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                </td>
                                <td>{{ $passenger->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('passengers.show', $passenger->id) }}"
                                            class="btn btn-icon btn-sm btn-text-secondary rounded-pill me-1">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <form action="{{ route('passengers.destroy', $passenger->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-icon btn-sm btn-text-secondary rounded-pill show-confirm"
                                                onclick="return confirm('Are you sure you want to delete this passenger?')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No passengers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection