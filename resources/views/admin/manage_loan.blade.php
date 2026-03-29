<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Loan Agents</title>
    @include('admin.includes.header_links')
</head>

<body>

@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">

    <div class="page-head">
        <h1 class="page-title">Manage Loan Agents</h1>

        <div class="action-group">
            <a href="{{ route('exportLoans', request()->query()) }}" class="btn-action btn-manage">Export CSV</a>
            <a href="{{ route('createLoan') }}" class="btn-action btn-manage">
                + Add Loan Agent
            </a>
        </div>
    </div>

    <div class="property-form">

        <div class="filter-section mb-4">
    <form action="{{ route('manageLoan') }}" method="GET" class="row g-3 align-items-end">

        <div class="col-md-4">
            <label class="form-label">Search Agent</label>
            <input type="text" name="search" class="form-control" 
                   placeholder="Name or Mobile Number"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Loan Type</label>
            <select name="loan_type" class="form-select">
                <option value="">All Loan Types</option>
                <option value="House Loan" {{ request('loan_type') == 'House Loan' ? 'selected' : '' }}>House Loan</option>
                <option value="Open Plot Loan" {{ request('loan_type') == 'Open Plot Loan' ? 'selected' : '' }}>Open Plot Loan</option>
                <option value="Mortgage Loan" {{ request('loan_type') == 'Mortgage Loan' ? 'selected' : '' }}>Mortgage Loan</option>
                <option value="AGPA Loan" {{ request('loan_type') == 'AGPA Loan' ? 'selected' : '' }}>AGPA Loan</option>
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
            <a href="{{ route('manageLoan') }}" class="btn btn-secondary">Reset</a>
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
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Mobile</th>
                        <th>Bank Name</th>
                        <th>Bank Type</th>
                        <th>Loan Types</th>
                        <th>Constituency</th>
                        <th>District</th>
                        <th>State</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($agents as $agent)
                    <tr>
                        <td><input type="checkbox" class="agent-checkbox"></td>
                        <td>{{ ($agents->currentPage() - 1) * $agents->perPage() + $loop->iteration }}</td>
                        <td>{{ $agent->full_name }}</td>
                        <td>{{ $agent->mobile }}</td>
                        <td>{{ $agent->bank_name }}</td>
                        <td>{{ $agent->bank_type == 1 ? 'Government' : 'Private' }}</td>
                        <td>{{ $agent->loan_types }}</td>
                        <td>{{ $agent->constituency }}</td>
                        <td>{{ $agent->district }}</td>
                        <td>{{ $agent->state }}</td>
                        <td>
                            <span class="status-badge {{ $agent->status == 1 ? 'status-active' : 'status-pending' }}">
                                {{ $agent->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $agent->created_at->format('d M Y') }}</td>
                        <td class="action-cell">

                            <button class="action-btn btn-view"
                                data-id="{{ $agent->id }}"
                                data-name="{{ $agent->full_name }}"
                                data-mobile="{{ $agent->mobile }}"
                                data-bank="{{ $agent->bank_name }}"
                                data-bank-type="{{ $agent->bank_type }}"
                                data-loan-types="{{ $agent->loan_types }}"
                                data-constituency="{{ $agent->constituency }}"
                                data-district="{{ $agent->district }}"
                                data-state="{{ $agent->state }}"
                                data-status="{{ $agent->status == 1 ? 'Active' : 'Inactive' }}"
                                data-added="{{ $agent->created_at->format('d M Y') }}"
                                data-profile="{{ $agent->profile_photo ? asset('uploads/loans/' . $agent->profile_photo) : '' }}"
                                onclick="openViewModal(this)">
                                View
                            </button>

                            <a href="{{ route('editLoan', $agent->id) }}"
                               class="action-btn btn-edit"
                               style="text-decoration: none; display: inline-block;">Edit</a>

                            <form action="{{ route('deleteLoan', $agent->id) }}" method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this agent?')">
                                @csrf
                                <button type="submit" class="action-btn btn-delete">Delete</button>
                            </form>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="text-center">No loan agents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3">
            <div class="pagination-info">
                Showing {{ $agents->firstItem() ?? 0 }}–{{ $agents->lastItem() ?? 0 }} of {{ $agents->total() }} loan agents
            </div>
            <nav>
                {{ $agents->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>

    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewLoanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Loan Agent Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <img id="modal-profile" src="" alt="Profile"
                             class="rounded-circle"
                             style="width:100px;height:100px;object-fit:cover;border:2px solid #ddd;"
                             onerror="this.src='https://via.placeholder.com/100?text=No+Photo'">
                        <p class="mt-2 fw-bold" id="modal-name"></p>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr><th width="40%">Mobile</th>      <td id="modal-mobile"></td></tr>
                                <tr><th>Bank Name</th>               <td id="modal-bank"></td></tr>
                                <tr><th>Bank Type</th>               <td id="modal-bank-type"></td></tr>
                                <tr><th>Loan Types</th>              <td id="modal-loan-types"></td></tr>
                                <tr><th>Constituency</th>            <td id="modal-constituency"></td></tr>
                                <tr><th>District</th>                <td id="modal-district"></td></tr>
                                <tr><th>State</th>                   <td id="modal-state"></td></tr>
                                <tr><th>Status</th>                  <td id="modal-status"></td></tr>
                                <tr><th>Added On</th>                <td id="modal-added"></td></tr>
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
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.agent-checkbox').forEach(cb => cb.checked = this.checked);
    });

    function openViewModal(btn) {
        document.getElementById('modal-name').textContent         = btn.dataset.name;
        document.getElementById('modal-mobile').textContent       = btn.dataset.mobile;
        document.getElementById('modal-bank').textContent         = btn.dataset.bank;
        document.getElementById('modal-bank-type').textContent    = btn.dataset.bankType;
        document.getElementById('modal-loan-types').textContent   = btn.dataset.loanTypes;
        document.getElementById('modal-constituency').textContent = btn.dataset.constituency;
        document.getElementById('modal-district').textContent     = btn.dataset.district;
        document.getElementById('modal-state').textContent        = btn.dataset.state;
        document.getElementById('modal-added').textContent        = btn.dataset.added;

        const isActive = btn.dataset.status === 'Active';
        document.getElementById('modal-status').innerHTML =
            `<span class="status-badge ${isActive ? 'status-active' : 'status-pending'}">${btn.dataset.status}</span>`;

        document.getElementById('modal-profile').src =
            btn.dataset.profile || 'https://via.placeholder.com/100?text=No+Photo';

        new bootstrap.Modal(document.getElementById('viewLoanModal')).show();
    }
</script>

</body>
</html>