<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Architectures</title>
    @include('admin.includes.header_links')
    <style>
        /* Status Badge Color Definitions */
        .status-badge.status-active { 
            background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; 
        } /* Green: Approved, Completed */
        
        .status-badge.status-pending { 
            background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b; 
        } /* Orange: Under Approval */
        
        .status-badge.status-construction { 
            background-color: #e0f2fe; color: #075985; border: 1px solid #0ea5e9; 
        } /* Blue: Under Construction */
        
        .status-badge.status-design { 
            background-color: #f1f5f9; color: #475569; border: 1px solid #94a3b8; 
        } /* Grey: Concept / Design Stage */
    </style>
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">Manage Architectures</h1>
        <div class="action-group">
            <a href="{{ route('exportArchitectures', request()->query()) }}" class="btn-action btn-manage text-decoration-none">Export CSV</a>
            <a href="{{ route('createArchitecture') }}" class="btn-action btn-manage text-decoration-none">
                + Add New Architecture
            </a>
        </div>
    </div>

    <div class="property-form">
        {{-- Filter Section --}}
        <div class="filter-section mb-4">
            <form action="{{ route('manageArchitecture') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search Project</label>
                    <input type="text" name="search" class="form-control" placeholder="Project name or architect" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Project Type</label>
                    <select name="project_type" class="form-select">
                        <option value="">All Types</option>
                        @foreach(['Apartment Complex', 'Villa Community', 'Residential Plot Layout', 'Commercial Building', 'Mixed-Use Development', 'High-Rise Tower'] as $type)
                            <option value="{{ $type }}" {{ request('project_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="project_status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['Concept / Design Stage', 'Under Approval', 'Approved', 'Under Construction', 'Completed'] as $status)
                            <option value="{{ $status }}" {{ request('project_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn-save w-100">Submit</button>
                    <a href="{{ route('manageArchitecture') }}" class="btn btn-secondary text-decoration-none">Reset</a>
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
                        <th>Project Name</th>
                        <th>Type</th>
                        <th>Architect / Firm</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Submission Date</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($architectures as $project)
                    <tr>
                        <td><input type="checkbox" class="project-checkbox"></td>
                        <td>{{ ($architectures->currentPage() - 1) * $architectures->perPage() + $loop->iteration }}</td>
                        <td><strong>{{ $project->project_name }}</strong></td>
                        <td>{{ $project->project_type }}</td>
                        <td>{{ $project->architect_name }}</td>
                        <td>{{ $project->city ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusClass = match($project->project_status) {
                                    'Approved', 'Completed'   => 'status-active',
                                    'Under Approval'          => 'status-pending',
                                    'Under Construction'      => 'status-construction',
                                    'Concept / Design Stage'  => 'status-design',
                                    default                   => 'status-design'
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $project->project_status }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($project->submission_date)->format('d M Y') }}</td>
                        <td>{{ $project->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                <button class="action-btn btn-view" 
                                    data-name="{{ $project->project_name }}"
                                    data-type="{{ $project->project_type }}"
                                    data-architect="{{ $project->architect_name }}"
                                    data-license="{{ $project->license_no ?? 'N/A' }}"
                                    data-address="{{ $project->project_address }}"
                                    data-city="{{ $project->city }}"
                                    data-status="{{ $project->project_status }}"
                                    data-status-class="{{ $statusClass }}"
                                    data-desc="{{ $project->description }}"
                                    data-plan="{{ $project->plans ? asset('uploads/architectures/' . $project->plans) : '' }}"
                                    onclick="openViewModal(this)">View</button>
                                
                                <a href="{{ route('editArchitecture', $project->id) }}" class="action-btn btn-edit text-decoration-none">Edit</a>
                                
                                <form action="{{ route('deleteArchitecture', $project->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    <button type="submit" class="action-btn btn-delete border-0 bg-transparent">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center">No projects found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3 d-flex justify-content-between align-items-center">
            <div class="pagination-info text-muted small">
                Showing {{ $architectures->firstItem() ?? 0 }}–{{ $architectures->lastItem() ?? 0 }} of {{ $architectures->total() }} architectures
            </div>
            <nav>
                {{ $architectures->appends(request()->query())->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    </div>
</div>

{{-- VIEW MODAL --}}
<div class="modal fade" id="viewArchitectureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Project Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><th width="30%" class="bg-light">Project Name:</th> <td id="modal-name" class="fw-bold"></td></tr>
                    <tr><th class="bg-light">Type:</th> <td id="modal-type"></td></tr>
                    <tr><th class="bg-light">Architect/Firm:</th> <td id="modal-architect"></td></tr>
                    <tr><th class="bg-light">License No:</th> <td id="modal-license"></td></tr>
                    <tr><th class="bg-light">Address:</th> <td id="modal-address"></td></tr>
                    <tr><th class="bg-light">Current Status:</th> <td><span id="modal-status-badge" class="status-badge"></span></td></tr>
                    <tr><th class="bg-light">Description:</th> <td id="modal-desc"></td></tr>
                    <tr id="plan-row">
                        <th class="bg-light">Drawing / Plan:</th> 
                        <td><a id="modal-plan" href="" target="_blank" class="btn btn-sm btn-primary">View Document</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.includes.footer_links')

<script>
    // Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.project-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // View Modal Data Binding
    function openViewModal(btn) {
        document.getElementById('modal-name').textContent = btn.dataset.name;
        document.getElementById('modal-type').textContent = btn.dataset.type;
        document.getElementById('modal-architect').textContent = btn.dataset.architect;
        document.getElementById('modal-license').textContent = btn.dataset.license;
        document.getElementById('modal-address').textContent = btn.dataset.address + ", " + btn.dataset.city;
        document.getElementById('modal-desc').textContent = btn.dataset.desc;
        
        // Status Badge Logic in Modal
        const statusBadge = document.getElementById('modal-status-badge');
        statusBadge.textContent = btn.dataset.status;
        statusBadge.className = 'status-badge ' + btn.dataset.statusClass;

        const planLink = document.getElementById('modal-plan');
        if (btn.dataset.plan) {
            document.getElementById('plan-row').style.display = 'table-row';
            planLink.href = btn.dataset.plan;
        } else {
            document.getElementById('plan-row').style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('viewArchitectureModal')).show();
    }
</script>
</body>
</html>