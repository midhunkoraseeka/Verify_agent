<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Approvals</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">Approval Master Data</h1>
        <div class="action-group">
            <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addApprovalModal">+ Add Approval</button>
        </div>
    </div>

    <div class="property-form">
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
                        <th style="width: 80px;">#</th>
                        <th>Approval Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $approval)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $approval->approval_name }}</strong></td>
                        <td>
                            <span class="status-badge {{ $approval->status == 1 ? 'status-active' : 'status-inactive' }}">
                                {{ $approval->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $approval->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                <button type="button" class="action-btn btn-edit me-2" data-bs-toggle="modal" data-bs-target="#editApprovalModal{{ $approval->id }}">Edit</button>
                                <a href="{{ route('deleteApproval', $approval->id) }}" class="action-btn btn-delete text-decoration-none" onclick="return confirm('Delete this record?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editApprovalModal{{ $approval->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <form action="{{ route('updateApproval', $approval->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header"><h5>Edit Approval</h5></div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Approval Name <span class="req">*</span></label>
                                            <input type="text" name="approval_name" class="form-control" value="{{ $approval->approval_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $approval->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $approval->status == 0 ? 'selected' : '' }}>Inactive</option>
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
                    @empty
                    <tr><td colspan="5" class="text-center py-4">No data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addApprovalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('storeApproval') }}" method="POST">
                @csrf
                <div class="modal-header"><h5>Add New Approval</h5></div>
                <div class="modal-body">
                    <label class="form-label">Approval Name <span class="req">*</span></label>
                    <input type="text" name="approval_name" class="form-control" placeholder="e.g. HMDA, DTCP" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.includes.footer_links')
</body>
</html>