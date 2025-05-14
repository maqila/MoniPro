<x-dashboard>
    <div class="page-heading">
        <h3>Edit Role: {{ $user->name }}</h3>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>User</option>
                            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Manager</option>
                            <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>Superadmin</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Update Role</button>
                </form>
            </div>
        </div>
    </div>
</x-dashboard>
