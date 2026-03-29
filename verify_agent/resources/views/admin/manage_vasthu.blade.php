<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Vasthu Consultants</title>
    @include('admin.includes.header_links')
</head>

<body>

@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">

    <div class="page-head">
        <h1 class="page-title">Manage Vasthu Consultants</h1>

        <div class="action-group">
            <a href="{{ route('exportVasthu', request()->query()) }}" class="btn-action btn-manage">Export CSV</a>
            <a href="{{ route('createVasthu') }}" class="btn-action btn-manage">
                + Add Vasthu Consultant
            </a>
        </div>
    </div>

    <div class="property-form">

        <div class="filter-section mb-4">
    <form action="{{ route('manageVasthu') }}" method="GET" class="row g-3 align-items-end">

        <div class="col-md-4">
            <label class="form-label">Search Consultant</label>
            <input type="text" name="search" class="form-control" 
                   placeholder="Name or Mobile Number" 
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Vasthu Service</label>
            <select name="vasthu_service" class="form-select">
                <option value="">All Services</option>
                <option value="Home" {{ request('vasthu_service') == 'Home' ? 'selected' : '' }}>Home</option>
                <option value="Open Plots" {{ request('vasthu_service') == 'Open Plots' ? 'selected' : '' }}>Open Plots</option>
                <option value="Bore Point" {{ request('vasthu_service') == 'Bore Point' ? 'selected' : '' }}>Bore Point</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">District</label>
            <input type="text" name="district" class="form-control" 
                   placeholder="Hyderabad" 
                   value="{{ request('district') }}">
        </div>

        <div class="col-md-2 d-flex gap-2 align-items-end">
            <button type="submit" class="btn-save w-100">Apply</button>
            <a href="{{ route('manageVasthu') }}" class="btn btn-secondary">Reset</a>
        </div>

    </form>
</div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Mobile</th>
                        <th>Vasthu Services</th>
                        <th>Constituency</th>
                        <th>District</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($consultants as $consultant)
                    <tr>
                        <td>{{ ($consultants->currentPage() - 1) * $consultants->perPage() + $loop->iteration }}</td>
                        <td><strong>{{ $consultant->full_name }}</strong></td>
                        <td>{{ $consultant->mobile }}</td>
                        <td>{{ $consultant->vasthu_services }}</td>
                        <td>{{ $consultant->constituency }}</td>
                        <td>{{ $consultant->district }}</td>
                        <td>{{ $consultant->state }}</td>
                        <td>
                            <span class="status-badge {{ $consultant->status == 1 ? 'status-active' : 'status-pending' }}">
                                {{ $consultant->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $consultant->created_at->format('d M Y') }}</td>
                        <td class="action-cell">
                            <button class="action-btn btn-view" 
                                data-name="{{ $consultant->full_name }}"
                                data-mobile="{{ $consultant->mobile }}"
                                data-services="{{ $consultant->vasthu_services }}"
                                data-constituency="{{ $consultant->constituency }}"
                                data-district="{{ $consultant->district }}"
                                data-state="{{ $consultant->state }}"
                                data-location="{{ $consultant->office_location }}"
                                data-status="{{ $consultant->status == 1 ? 'Active' : 'Inactive' }}"
                                data-added="{{ $consultant->created_at->format('d M Y') }}"
                                data-profile="{{ $consultant->profile_photo ? asset('uploads/vasthu/' . $consultant->profile_photo) : '' }}"
                                onclick="openViewModal(this)">View</button>
                            
                            <a href="{{ route('editVasthu', $consultant->id) }}" class="action-btn btn-edit" style="text-decoration:none;">Edit</a>
                            
                            <form action="{{ route('deleteVasthu', $consultant->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this consultant?')">
                                @csrf
                                <button type="submit" class="action-btn btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No vasthu consultants found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3">
            <div class="pagination-info">
                Showing {{ $consultants->firstItem() ?? 0 }}–{{ $consultants->lastItem() ?? 0 }} of {{ $consultants->total() }} vasthu consultants
            </div>

            <nav>
                {{ $consultants->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>

    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewVasthuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Consultant Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img id="modal-profile" src="" alt="Profile"
                             class="img-fluid rounded border"
                             style="max-height: 200px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/150?text=No+Photo'">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr><th width="40%">Full Name</th><td id="modal-name"></td></tr>
                                <tr><th>Mobile</th><td id="modal-mobile"></td></tr>
                                <tr><th>Services</th><td id="modal-services"></td></tr>
                                <tr><th>Constituency</th><td id="modal-constituency"></td></tr>
                                <tr><th>District</th><td id="modal-district"></td></tr>
                                <tr><th>State</th><td id="modal-state"></td></tr>
                                <tr><th>Office Location</th><td id="modal-location"></td></tr>
                                <tr><th>Status</th><td id="modal-status"></td></tr>
                                <tr><th>Added On</th><td id="modal-added"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer_links')

<script>
    function openViewModal(btn) {
        document.getElementById('modal-name').textContent = btn.dataset.name;
        document.getElementById('modal-mobile').textContent = btn.dataset.mobile;
        document.getElementById('modal-services').textContent = btn.dataset.services;
        document.getElementById('modal-constituency').textContent = btn.dataset.constituency;
        document.getElementById('modal-district').textContent = btn.dataset.district;
        document.getElementById('modal-state').textContent = btn.dataset.state;
        document.getElementById('modal-location').textContent = btn.dataset.location;
        document.getElementById('modal-added').textContent = btn.dataset.added;

        const isActive = btn.dataset.status === 'Active';
        document.getElementById('modal-status').innerHTML =
            `<span class="status-badge ${isActive ? 'status-active' : 'status-pending'}">${btn.dataset.status}</span>`;

        document.getElementById('modal-profile').src =
            btn.dataset.profile || 'https://via.placeholder.com/150?text=No+Photo';

        new bootstrap.Modal(document.getElementById('viewVasthuModal')).show();
    }
</script>

</body>
</html>