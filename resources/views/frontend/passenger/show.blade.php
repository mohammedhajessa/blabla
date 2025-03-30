@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Passenger Details</h5>
                        <a href="{{ route('passengers.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar me-3">
                                        @if($passenger->profile_photo)
                                            <img src="{{ asset($passenger->profile_photo) }}" alt="Profile Photo" class="rounded-circle" width="50">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ substr($passenger->name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ ucfirst($passenger->name) }}</h5>
                                        <small class="text-muted">Passenger ID: #{{ $passenger->id }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mb-3">
                                <span class="badge bg-label-{{ $passenger->status == 'active' ? 'success' : 'danger' }} me-1">
                                    {{ ucfirst($passenger->status ?? 'inactive') }}
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Email</h6>
                                    <p>{{ $passenger->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Phone Number</h6>
                                    <p>{{ $passenger->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Address</h6>
                                    <p>{{ $passenger->address ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Date of Birth</h6>
                                    <p>{{ $passenger->dob ? date('d M Y', strtotime($passenger->dob)) : 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Registered On</h6>
                                    <p>{{ $passenger->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="fw-semibold">Last Updated</h6>
                                    <p>{{ $passenger->updated_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Recent Journeys</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Journey ID</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($passenger->journeys ?? [] as $journey)
                                        <tr>
                                            <td>#{{ $journey->id }}</td>
                                            <td>{{ $journey->from_city }}</td>
                                            <td>{{ $journey->to_city }}</td>
                                            <td>{{ $journey->journey_date }}</td>
                                            <td>
                                                <span class="badge bg-label-{{ $journey->status == 'completed' ? 'success' : ($journey->status == 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($journey->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('journeys.show', $journey->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-eye me-1"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No journeys found for this passenger</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{--  <div class="mt-4 d-flex gap-2">
                            <a href="{{ route('passengers.edit', $passenger->id) }}" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i> Edit Passenger
                            </a>
                            <form action="{{ route('passengers.destroy', $passenger->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger show-confirm"
                                    onclick="return confirm('Are you sure you want to delete this passenger?')">
                                    <i class="ti ti-trash me-1"></i> Delete Passenger
                                </button>
                            </form>
                        </div>  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection