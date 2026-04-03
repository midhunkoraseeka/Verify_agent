<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Constituencies</title>
    @include('admin.includes.header_links')
</head>
<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Constituency Master Data</h1>
            <div class="action-group" style="display: flex; align-items: center; gap: 12px;">
                <div class="search-wrapper" style="position: relative;">
                    <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#888; pointer-events:none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                    </svg>
                    <input type="text" id="conSearch" placeholder="Search constituency..." style="padding: 8px 12px 8px 32px; border: 1px solid #dde1e7; border-radius: 6px; font-size: 14px; outline: none; min-width: 220px; background: #fff;">
                </div>
                <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Constituency</button>
            </div>
        </div>

        <div class="property-form">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>Constituency Name</th>
                            <th>Status</th>
                            <th>Added On</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($constituencies as $con)
                            <tr data-name="{{ strtolower($con->constituency_name) }}">
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $con->constituency_name }}</strong></td>
                                <td>
                                    @if($con->status == 1)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $con->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="action-cell justify-content-end">
                                        <button type="button" class="action-btn btn-edit me-2" 
                                            onclick="editCon('{{ $con->id }}', '{{ $con->constituency_name }}', '{{ $con->status }}')"
                                            data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                        <a href="{{ route('deleteConstituency', $con->id) }}" class="action-btn btn-delete text-decoration-none" onclick="return confirm('Move to trash?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add New Constituency</h5></div>
                <form action="{{ route('storeConstituency') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-start">
                            <label class="form-label">Constituency Name <span class="req">*</span></label>
                            <input type="text" name="constituency_name" class="form-control" placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save px-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Constituency</h5></div>
                <form id="editForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-start">
                            <label class="form-label">Constituency Name <span class="req">*</span></label>
                            <input type="text" name="constituency_name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save px-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.includes.footer_links')

    <script>
        function editCon(id, name, status) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_status').value = status;
            document.getElementById('editForm').action = "{{ url('/updateConstituency') }}/" + id;
        }

        document.getElementById('conSearch').addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows  = document.querySelectorAll('tbody tr[data-name]');
            rows.forEach(row => {
                row.style.display = row.getAttribute('data-name').includes(query) ? '' : 'none';
            });
        });
    </script>
</body>
</html>