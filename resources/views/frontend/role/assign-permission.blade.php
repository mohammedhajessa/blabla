<div class="modal fade" id="assignPermissionModal{{ $role->id }}" tabindex="-1" aria-labelledby="assignPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignPermissionModalLabel">Assign Permission to {{ $role->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('roles.assign-permission', $role->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Permissions</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll{{ $role->id }}">
                                <label class="form-check-label" for="selectAll{{ $role->id }}">Select All</label>
                            </div>
                        </div>
                        <div class="permissions-container border rounded p-3">
                            @foreach($permissions as $permission)
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                        id="permission{{ $permission->id }}_{{ $role->id }}"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission{{ $permission->id }}_{{ $role->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll{{ $role->id }}');
        const permissionCheckboxes = document.querySelectorAll('#assignPermissionModal{{ $role->id }} .permission-checkbox');

        selectAllCheckbox.addEventListener('change', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        // Update "Select All" checkbox state based on individual checkboxes
        function updateSelectAllCheckbox() {
            const allChecked = Array.from(permissionCheckboxes).every(checkbox => checkbox.checked);
            const someChecked = Array.from(permissionCheckboxes).some(checkbox => checkbox.checked);

            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }

        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllCheckbox);
        });

        // Initialize state
        updateSelectAllCheckbox();
    });
</script>
