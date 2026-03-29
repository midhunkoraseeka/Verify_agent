<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage BHK Types</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">BHK Types Master Data</h1>
        <div class="action-group">
            <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addBhkModal">
                + Add BHK Type
            </button>
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
                        <th>BHK Configuration Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bhks as $bhk)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $bhk->bhk_type_name }}</strong></td>
                        <td>
                            <span class="status-badge {{ $bhk->status == 1 ? 'status-active' : 'status-inactive' }}">
                                {{ $bhk->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $bhk->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                {{-- Trigger Edit Modal --}}
                                <button type="button" class="action-btn btn-edit me-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editBhkModal{{ $bhk->id }}">
                                    Edit
                                </button>

                                <a href="{{ route('deleteBhkType', $bhk->id) }}" 
                                   class="action-btn btn-delete text-decoration-none" 
                                   onclick="return confirm('Delete this record?')">
                                   Delete
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Dynamic Edit Modal for Each Row --}}
                    <div class="modal fade" id="editBhkModal{{ $bhk->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <form action="{{ route('updateBhkType', $bhk->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit BHK Type</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">BHK Name <span class="req">*</span></label>
                                            <input type="text" name="bhk_type_name" class="form-control" value="{{ $bhk->bhk_type_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $bhk->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $bhk->status == 0 ? 'selected' : '' }}>Inactive</option>
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
                    <tr>
                        <td colspan="5" class="text-center py-4">No BHK data found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addBhkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('storeBhkType') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add BHK Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">BHK Name <span class="req">*</span></label>
                    <input type="text" name="bhk_type_name" class="form-control" placeholder="e.g. 2BHK, 3BHK" required>
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