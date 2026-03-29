<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Advocates</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">

    <div class="page-head">
        <h1 class="page-title">Manage Advocates</h1>

        <div class="action-group">
            <a href="{{ route('exportAdvocates', request()->query()) }}" class="btn-action btn-manage text-decoration-none">Export CSV</a>
            <a href="{{ route('createAdvocate') }}" class="btn-action btn-manage text-decoration-none">
                + Add New Advocate
            </a>
        </div>
    </div>

    <div class="property-form">

        <div class="filter-section mb-4">
    <form action="{{ route('manageAdvocate') }}" method="GET" class="row g-3 align-items-end">

        <div class="col-md-4">
            <label class="form-label">Search Advocate</label>
            <input type="text" name="search" class="form-control" placeholder="Name or Mobile Number" value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Legal Service</label>
            <select name="legal_service" class="form-select">
                <option value="">All Services</option>
                <option value="Agriculture Land" {{ request('legal_service') == 'Agriculture Land' ? 'selected' : '' }}>Agriculture Land</option>
                <option value="GP Plots" {{ request('legal_service') == 'GP Plots' ? 'selected' : '' }}>GP Plots</option>
                <option value="DTCP" {{ request('legal_service') == 'DTCP' ? 'selected' : '' }}>DTCP</option>
                <option value="HMDA" {{ request('legal_service') == 'HMDA' ? 'selected' : '' }}>HMDA</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">District</label>
            <input type="text" name="district" class="form-control" placeholder="Hyderabad" value="{{ request('district') }}">
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn-save w-100">Apply</button>
            <a href="{{ route('manageAdvocate') }}" class="btn btn-secondary text-decoration-none">Reset</a>
        </div>

    </form>
</div>
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
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Mobile</th>
                        <th>Legal Services</th>
                        <th>Constituency</th>
                        <th>District</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($advocates as $advocate)
                    <tr>
                        <td><input type="checkbox" class="advocate-checkbox"></td>
                        <td>{{ ($advocates->currentPage() - 1) * $advocates->perPage() + $loop->iteration }}</td>
                        <td><strong>{{ $advocate->full_name }}</strong></td>
                        <td>{{ $advocate->mobile }}</td>
                        <td>{{ $advocate->legal_services }}</td>
                        <td>{{ $advocate->constituency ?? 'N/A' }}</td>
                        <td>{{ $advocate->district ?? 'N/A' }}</td>
                        <td>{{ $advocate->state ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge {{ $advocate->status == 1 ? 'status-active' : 'status-pending' }}">
                                {{ $advocate->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $advocate->created_at->format('d M Y') }}</td>
                        <td class="action-cell">
                            <button class="action-btn btn-view" 
                                data-name="{{ $advocate->full_name }}"
                                data-mobile="{{ $advocate->mobile }}"
                                data-services="{{ $advocate->legal_services }}"
                                data-district="{{ $advocate->district }}"
                                data-office="{{ $advocate->office_location }}"
                                data-status="{{ $advocate->status == 1 ? 'Active' : 'Inactive' }}"
                                data-photo="{{ $advocate->profile_photo ? asset('uploads/advocates/' . $advocate->profile_photo) : '' }}"
                                onclick="openViewModal(this)">View</button>
                            
                            <a href="{{ route('editAdvocate', $advocate->id) }}" class="action-btn btn-edit text-decoration-none">Edit</a>
                            
                            <form action="{{ route('deleteAdvocate', $advocate->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                <button type="submit" class="action-btn btn-delete border-0">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No advocates found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3 d-flex justify-content-between align-items-center">
            <div class="pagination-info">
                Showing {{ $advocates->firstItem() ?? 0 }}–{{ $advocates->lastItem() ?? 0 }} of {{ $advocates->total() }} advocates
            </div>
            <nav>
                {{ $advocates->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>

    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewAdvocateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advocate Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img id="modal-photo" src="" class="img-fluid rounded border" style="max-height: 200px;" onerror="this.src='https://via.placeholder.com/150'">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm">
                            <tr><th width="30%">Name:</th> <td id="modal-name"></td></tr>
                            <tr><th>Mobile:</th> <td id="modal-mobile"></td></tr>
                            <tr><th>Services:</th> <td id="modal-services"></td></tr>
                            <tr><th>District:</th> <td id="modal-district"></td></tr>
                            <tr><th>Office:</th> <td id="modal-office"></td></tr>
                            <tr><th>Status:</th> <td id="modal-status"></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer_links')

<script>
    // Select All functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.advocate-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // View Modal Data Binding
    function openViewModal(btn) {
        document.getElementById('modal-name').textContent = btn.dataset.name;
        document.getElementById('modal-mobile').textContent = btn.dataset.mobile;
        document.getElementById('modal-services').textContent = btn.dataset.services;
        document.getElementById('modal-district').textContent = btn.dataset.district;
        document.getElementById('modal-office').textContent = btn.dataset.office;
        document.getElementById('modal-status').textContent = btn.dataset.status;
        document.getElementById('modal-photo').src = btn.dataset.photo || 'https://via.placeholder.com/150';
        
        new bootstrap.Modal(document.getElementById('viewAdvocateModal')).show();
    }
</script>
</body>
</html>