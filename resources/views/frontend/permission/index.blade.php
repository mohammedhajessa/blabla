@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Permissions Management</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                        <i class="ti ti-plus me-1"></i>Add Permission
                    </button>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-label-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal{{ $permission->id }}" data-bs-toggle="tooltip" title="Edit Permission">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            @include('frontend.permission.edit', ['permission' => $permission])
                                            <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                class="btn btn-sm btn-label-danger"
                                                data-bs-toggle="tooltip"
                                                title="Delete Permission">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="ti ti-lock-off ti-5x text-primary"></i>
                                        </div>
                                        <h4>No permissions found</h4>
                                        <p class="text-muted">Start by creating new permissions</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.permission.create')
@endsection
