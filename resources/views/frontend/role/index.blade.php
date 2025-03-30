@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Roles Management</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="ti ti-plus me-1"></i>Create Role
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
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-label-primary" data-bs-toggle="modal" data-bs-target="#viewPermissionsModal{{ $role->id }}">
                                            View Permissions ({{ count($role->permissions) }})
                                        </button>
                                        @include('frontend.role.view-permissions', ['role' => $role])
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('roles.destroy', $role->id) }}"
                                                method="POST"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteRoleModal{{ $role->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                class="btn btn-sm btn-label-danger"
                                                data-bs-toggle="tooltip"
                                                title="Delete Role">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-label-primary" data-bs-toggle="modal" data-bs-target="#assignPermissionModal{{ $role->id }}" data-bs-toggle="tooltip" title="Assign Permission">
                                            <i  class="ti ti-users-plus"></i>
                                        </button>
                                        @include('frontend.role.assign-permission', ['role' => $role, 'permissions' => $permissions])
                                        <button type="button" class="btn btn-sm btn-label-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}" data-bs-toggle="tooltip" title="Edit Role">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        @include('frontend.role.edit', ['role' => $role])
                                    </div>
                                </td>
                            </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="ti ti-users-minus ti-5x text-primary"></i>
                                        </div>
                                        <h4>No roles found</h4>
                                        <p class="text-muted">Start by creating new roles</p>
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

@include('frontend.role.create')

@endsection