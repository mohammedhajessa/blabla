<div class="modal fade" id="viewPermissionsModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $role->name }} Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @forelse($role->permissions as $permission)
                    <div class="d-inline-block position-relative me-2 mb-2">
                        <span class="badge bg-label-primary py-2 pe-4 ps-3">{{ $permission->name }}</span>
                        <form action="{{ route('roles.assignPermissionDestroy', ['role' => $role, 'permission' => $permission]) }}" method="POST" class="position-absolute top-0 start-100 translate-middle">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-icon p-0 bg-transparent shadow-none" data-bs-toggle="tooltip" data-bs-delay="0" title="Remove permission">
                                <i class="ti ti-x fs-12 text-primary"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <span class="badge bg-label-secondary">No permissions assigned</span>
                @endforelse
            </div>
        </div>
    </div>
</div>