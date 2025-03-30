<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Create Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <select name="name" id="name" class="form-control">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="super-admin">Super Admin</option>
                            <option value="driver">Driver</option>
                            <option value="passenger">Passenger</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign Permissions</label>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="addPermissionsNow">
                            <label class="form-check-label" for="addPermissionsNow">Add permissions now</label>
                        </div>
                        <div id="permissionsContainer" class="border rounded p-3 mt-2" style="display: none;">
                            <div class="mb-2">Select permissions to assign:</div>
                            @foreach(Spatie\Permission\Models\Permission::all() as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}">
                                <label class="form-check-label" for="permission{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addPermissionsCheckbox = document.getElementById('addPermissionsNow');
    const permissionsContainer = document.getElementById('permissionsContainer');

    addPermissionsCheckbox.addEventListener('change', function() {
        permissionsContainer.style.display = this.checked ? 'block' : 'none';
    });
});
</script>
