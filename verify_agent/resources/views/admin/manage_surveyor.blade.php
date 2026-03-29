<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Land Surveyors</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">

    <div class="page-head">
        <h1 class="page-title">Manage Land Surveyors</h1>

        <div class="action-group">
            <a href="{{ route('exportSurveyors', request()->query()) }}" class="btn-action btn-manage">Export CSV</a>
            <a href="{{ route('createSurveyor') }}" class="btn-action btn-manage">
                + Add Land Surveyor
            </a>
        </div>
    </div>

    <div class="property-form">

        <div class="filter-section mb-4">
            <form action="{{ route('manageSurveyor') }}" method="GET" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Search Surveyor</label>
                    <input type="text" name="search" class="form-control" placeholder="Name or Mobile Number" value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Survey Service</label>
                    <select name="survey_service" class="form-select">
                        <option value="">All Services</option>
                        <option value="Agriculture Land" {{ request('survey_service') == 'Agriculture Land' ? 'selected' : '' }}>Agriculture Land</option>
                        <option value="GP Plots" {{ request('survey_service') == 'GP Plots' ? 'selected' : '' }}>GP Plots</option>
                        <option value="DTCP" {{ request('survey_service') == 'DTCP' ? 'selected' : '' }}>DTCP</option>
                        <option value="HMDA Venture Layouts" {{ request('survey_service') == 'HMDA Venture Layouts' ? 'selected' : '' }}>HMDA Venture Layouts</option>
                        <option value="House Planning" {{ request('survey_service') == 'House Planning' ? 'selected' : '' }}>House Planning</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">District</label>
                    <input type="text" name="district" class="form-control" placeholder="Hyderabad" value="{{ request('district') }}">
                </div>

                <div class="col-md-2 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn-save w-100">Apply</button>
                    <a href="{{ route('manageSurveyor') }}" class="btn btn-secondary">Reset</a>
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
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Mobile</th>
                        <th>Survey Services</th>
                        <th>Constituency</th>
                        <th>District</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($surveyors as $surveyor)
                    <tr>
                        <td><input type="checkbox" class="surveyor-checkbox"></td>
                        <td>{{ ($surveyors->currentPage() - 1) * $surveyors->perPage() + $loop->iteration }}</td>
                        <td><strong>{{ $surveyor->full_name }}</strong></td>
                        <td>{{ $surveyor->mobile }}</td>
                        <td>{{ $surveyor->survey_services }}</td>
                        <td>{{ $surveyor->constituency }}</td>
                        <td>{{ $surveyor->district }}</td>
                        <td>{{ $surveyor->state }}</td>
                        <td>
                            <span class="status-badge {{ $surveyor->status == 1 ? 'status-active' : 'status-pending' }}">
                                {{ $surveyor->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $surveyor->created_at->format('d M Y') }}</td>
                        <td class="action-cell">
                            <button class="action-btn btn-view" 
                                data-name="{{ $surveyor->full_name }}"
                                data-mobile="{{ $surveyor->mobile }}"
                                data-services="{{ $surveyor->survey_services }}"
                                data-constituency="{{ $surveyor->constituency }}"
                                data-district="{{ $surveyor->district }}"
                                data-state="{{ $surveyor->state }}"
                                data-office="{{ $surveyor->office_location }}"
                                data-status="{{ $surveyor->status == 1 ? 'Active' : 'Inactive' }}"
                                data-photo="{{ $surveyor->profile_photo ? asset('uploads/surveyors/' . $surveyor->profile_photo) : '' }}"
                                onclick="openViewModal(this)">View</button>
                            
                            <a href="{{ route('editSurveyor', $surveyor->id) }}" class="action-btn btn-edit" style="text-decoration:none;">Edit</a>
                            
                            <form action="{{ route('deleteSurveyor', $surveyor->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                <button type="submit" class="action-btn btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No surveyors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3">
            <div class="pagination-info">
                Showing {{ $surveyors->firstItem() ?? 0 }}–{{ $surveyors->lastItem() ?? 0 }} of {{ $surveyors->total() }} surveyors
            </div>

            <nav>
                {{ $surveyors->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>

    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewSurveyorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Surveyor Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img id="modal-photo" src="" class="img-fluid rounded border" style="max-height: 200px;" onerror="this.src='https://via.placeholder.com/150'">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm">
                            <tr><th>Name:</th> <td id="modal-name"></td></tr>
                            <tr><th>Mobile:</th> <td id="modal-mobile"></td></tr>
                            <tr><th>Services:</th> <td id="modal-services"></td></tr>
                            <tr><th>Constituency:</th> <td id="modal-constituency"></td></tr>
                            <tr><th>District:</th> <td id="modal-district"></td></tr>
                            <tr><th>State:</th> <td id="modal-state"></td></tr>
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
    // Select All Checkbox logic
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.surveyor-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // View Modal Logic
    function openViewModal(btn) {
        document.getElementById('modal-name').textContent = btn.dataset.name;
        document.getElementById('modal-mobile').textContent = btn.dataset.mobile;
        document.getElementById('modal-services').textContent = btn.dataset.services;
        document.getElementById('modal-constituency').textContent = btn.dataset.constituency;
        document.getElementById('modal-district').textContent = btn.dataset.district;
        document.getElementById('modal-state').textContent = btn.dataset.state;
        document.getElementById('modal-office').textContent = btn.dataset.office;
        document.getElementById('modal-status').textContent = btn.dataset.status;
        document.getElementById('modal-photo').src = btn.dataset.photo || 'https://via.placeholder.com/150';
        
        new bootstrap.Modal(document.getElementById('viewSurveyorModal')).show();
    }
</script>

</body>
</html>