<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Facing</title>
    @include('admin.includes.header_links')
</head>

<body>

@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">

    <div class="page-head">
        <h1 class="page-title">Facing Master Data</h1>
        <div class="action-group">
            <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addFacingModal">
                + Add New Facing
            </button>
        </div>
    </div>

    <div class="property-form">

        {{-- Success/Error Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Facing Direction Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($facings as $facing)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $facing->facing_name }}</strong></td>
                        <td>
                            @if($facing->status == 1)
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $facing->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                {{-- Updated Edit Trigger --}}
                                <button type="button" class="action-btn btn-edit me-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editFacingModal{{ $facing->id }}">
                                    Edit
                                </button>

                                <a href="{{ route('deleteFacing', $facing->id) }}" 
                                   class="action-btn btn-delete text-decoration-none" 
                                   onclick="return confirm('Move this record to trash?')">
                                   Delete
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- DYNAMIC EDIT MODAL --}}
                    <div class="modal fade" id="editFacingModal{{ $facing->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Facing Direction</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('updateFacing', $facing->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Facing Name <span class="req">*</span></label>
                                            <input type="text" name="facing_name" class="form-control" value="{{ $facing->facing_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $facing->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $facing->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn-save px-4">Update Facing</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No facing data found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addFacingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Facing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('storeFacing') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Facing Name <span class="req">*</span></label>
                        <input type="text" name="facing_name" class="form-control" placeholder="e.g. East, North-West" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save px-4">Save Facing</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.includes.footer_links')

</body>
</html>