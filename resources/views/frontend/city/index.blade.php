@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span class="text-heading">Total Cities</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $cities->count() }}</h4>
                                </div>
                                <small class="mb-0">All registered cities</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-building ti-26px"></i>
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
                                <span class="text-heading">Capital Cities</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $cities->where('is_capital', true)->count() }}</h4>
                                </div>
                                <small class="mb-0">Regional capitals</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-building-skyscraper ti-26px"></i>
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
                                <span class="text-heading">Regions</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $cities->pluck('region_id')->unique()->count() }}</h4>
                                </div>
                                <small class="mb-0">Total regions covered</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ti ti-map ti-26px"></i>
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
                                <span class="text-heading">New Cities</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">{{ $cities->where('created_at', '>=', now()->subMonth())->count() }}</h4>
                                </div>
                                <small class="mb-0">Added this month</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-building-community ti-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cities List Table -->
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Cities Management</h5>
                <a href="{{ route('cities.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add New City
                </a>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-cities table">
                    <thead class="border-top">
                        <tr>
                            <th>ID</th>
                            <th>City Name</th>
                            <th>Region</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst($city->name) }}</td>
                            <td>{{ ucfirst($city->region->name ?? 'No Region') }}</td>
                            <td>
                                <span class="badge bg-label-{{ $city->region ? 'success' : 'primary' }}">
                                    {{ $city->region ? 'Region' : 'City' }}
                                </span>
                            </td>
                            <td>{{ $city->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('cities.edit', $city->id) }}"
                                        class="btn btn-icon btn-sm btn-text-secondary rounded-pill">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('cities.destroy', $city->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-icon btn-sm btn-text-secondary rounded-pill show-confirm"
                                            onclick="return confirm('Are you sure you want to delete this city?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No cities found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
