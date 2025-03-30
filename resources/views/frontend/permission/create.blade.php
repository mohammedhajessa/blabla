<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <button
                    type="button"
                    class="btn-close btn-pinned"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="mb-2">Add New Permission</h4>
                    <p>Permissions you may use and assign to your users.</p>
                </div>
                <form action="{{ route('permissions.store') }}" method="POST" class="row">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label" for="modalPermissionName">Permission Name</label>
                        <select name="name" id="modalPermissionName" class="form-control">
                        </select>
                    </div>
                    <div class="col-12 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="corePermission" name="is_core" value="1" />
                            <label class="form-check-label" for="corePermission"> Set as core permission </label>
                        </div>
                    </div>
                    <div class="col-12 text-center demo-vertical-spacing">
                        <button type="submit" class="btn btn-primary me-4">Create Permission</button>
                        <button
                            type="reset"
                            class="btn btn-label-secondary"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            Discard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>