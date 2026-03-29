<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Vasthu Services</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">Vasthu Services Master Data</h1>
        <div class="action-group">
            <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                + Add Vasthu Service
            </button>
        </div>
    </div>

    <div class="property-form">
        {{-- Alerts --}}
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
                        <th>Service Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $service->service_name }}</strong></td>
                        <td>
                            <span class="status-badge {{ $service->status == 1 ? 'status-active' : 'status-inactive' }}">
                                {{ $service->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $service->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                <button type="button" class="action-btn btn-edit me-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editServiceModal{{ $service->id }}">
                                    Edit
                                </button>
                                <a href="{{ route('deleteVasthuService', $service->id) }}" 
                                   class="action-btn btn-delete text-decoration-none" 
                                   onclick="return confirm('Move to trash?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editServiceModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <form action="{{ route('updateVasthuService', $service->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Vasthu Service</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Service Name <span class="req">*</span></label>
                                            <input type="text" name="service_name" class="form-control" value="{{ $service->service_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $service->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $service->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn-save px-4">Update Service</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No Vasthu services found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Vasthu Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('storeVasthuService') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Service Name <span class="req">*</span></label>
                        <input type="text" name="service_name" class="form-control" placeholder="e.g. Home Vasthu, Bore Point" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save px-4">Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.includes.footer_links')
</body>
</html>