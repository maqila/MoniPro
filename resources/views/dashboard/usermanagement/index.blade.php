<x-dashboard>
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>User Management</h3>
    </div>

    <div class="page-content">
        <section class="section">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Users</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $roles = [0 => 'User', 1 => 'Admin', 2 => 'Manager', 3 => 'Superadmin'];
                                        @endphp
                                        {{ $roles[$user->role] ?? 'Unknown' }}
                                    </td>

                                    <td class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('users.editRole', $user->id) }}"
                                            class="btn btn-sm btn-warning shadow-sm rounded-pill">
                                            <i class="bi bi-person-gear me-1"></i> Edit Role
                                        </a>

                                        <a href="{{ route('users.editPassword', $user->id) }}"
                                            class="btn btn-sm btn-info text-white shadow-sm rounded-pill">
                                            <i class="bi bi-key-fill me-1"></i> Change Password
                                        </a>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger shadow-sm rounded-pill">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                            @if ($users->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No users found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-dashboard>
