<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users</title>
    @include('admin.includes.header_links')
</head>

<body>

@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">

    <div class="page-head">
        <h1 class="page-title">Manage Users</h1>

        <div class="action-group">
            <a href="{{ route('exportUsers', request()->query()) }}" class="btn-action btn-manage text-decoration-none">Export CSV</a>
            <a href="{{ route('createUser') }}" class="btn-action btn-manage text-decoration-none">+ Add New User</a>
        </div>
    </div>

    <div class="property-form">

        <div class="filter-section mb-4">
            <form action="{{ route('manageUser') }}" method="GET" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Search User</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, email or phone" value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" placeholder="Enter City" value="{{ request('city') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Constituency</label>
                    <input type="text" name="constituency" class="form-control" placeholder="Enter Constituency" value="{{ request('constituency') }}">
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn-save w-100">Filter</button>
                </div>
                
                <div class="col-md-1">
                    <a href="{{ route('manageUser') }}" class="btn btn-outline-secondary w-100">Clear</a>
                </div>

            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>#</th>
                        {{-- User ID removed --}}
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Constituency</th>
                        <th>Status</th>
                        <th>Created On</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td><input type="checkbox" class="user-checkbox"></td>
                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        
                        {{-- 1. Name column --}}
                        <td><strong>{{ $user->first_name }} {{ $user->last_name }}</strong></td>
                        
                        {{-- 2. Email column --}}
                        <td>{{ $user->email }}</td>
                        
                        {{-- 3. Phone column --}}
                        <td>{{ $user->mobile_number }}</td>
                        
                        {{-- 4. City column --}}
                        <td>{{ $user->city ?? '—' }}</td>
                        
                        {{-- 5. Constituency column --}}
                        <td>{{ $user->constituency ?? '—' }}</td>
                        
                        {{-- 6. Status column --}}
                        <td>
                            @if($user->status == 1)
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        
                        {{-- 7. Created On column --}}
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        
                        {{-- 8. Actions column --}}
                        <td class="action-cell">
                            <button class="action-btn btn-view">View</button>
                            <a href="{{ route('editUser', $user->id) }}" class="action-btn btn-edit text-decoration-none">Edit</a>
                            
                            <a href="{{ route('deleteUser', $user->id) }}" 
                               class="action-btn btn-delete text-decoration-none" 
                               onclick="return confirm('Are you sure you want to move this user to trash?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3 d-flex justify-content-between align-items-center">
            <div class="pagination-info text-muted small">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
            <nav>
                {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>

    </div>
</div>

@include('admin.includes.footer_links')

<script>
    // Functional Select All Logic
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

</body>
</html>



