<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Districts</title>
    @include('admin.includes.header_links')
</head>

<body>

    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">District Master Data</h1>
            <div class="action-group" style="display: flex; align-items: center; gap: 12px;">
                <div class="search-wrapper" style="position: relative;">
                    <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#888; pointer-events:none;"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                    </svg>
                    <input type="text" id="districtSearch" placeholder="Search districts..."
                        style="padding: 8px 12px 8px 32px; border: 1px solid #dde1e7; border-radius: 6px;
                               font-size: 14px; outline: none; min-width: 220px; background: #fff;
                               transition: border-color 0.2s, box-shadow 0.2s;"
                        onfocus="this.style.borderColor='#4f6ef7'; this.style.boxShadow='0 0 0 3px rgba(79,110,247,0.12)';"
                        onblur="this.style.borderColor='#dde1e7'; this.style.boxShadow='none';">
                </div>
                <button type="button" class="btn-action btn-manage" data-bs-toggle="modal"
                    data-bs-target="#addDistrictModal">
                    + Add New District
                </button>
            </div>
        </div>

        <div class="property-form">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>District Name</th>
                            <th>State</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($districts as $district)
                            <tr data-name="{{ strtolower($district->district_name) }} {{ strtolower($district->state->state_name ?? '') }}">
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $district->district_name }}</strong></td>
                                <td><span class="text-muted">{{ $district->state->state_name ?? 'N/A' }}</span></td>
                                <td>
                                    @if($district->status == 1)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="action-cell justify-content-end">
                                        <button type="button" class="action-btn btn-edit me-2" 
                                            onclick="editDistrict('{{ $district->id }}', '{{ $district->district_name }}', '{{ $district->state_id }}')"
                                            data-bs-toggle="modal" data-bs-target="#editDistrictModal">
                                            Edit
                                        </button>

                                        <a href="{{ route('deleteDistrict', $district->id) }}"
                                            class="action-btn btn-delete text-decoration-none"
                                            onclick="return confirm('Move this district to trash?')">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No district data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addDistrictModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New District</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('storeDistrict') }}" method="POST" novalidate onsubmit="return validateDistrictForm(this)">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-start">
                            <label class="form-label">Select State <span class="req">*</span></label>
                            <select name="state_id" class="form-select" required>
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-msg"></span>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">District Name <span class="req">*</span></label>
                            <input type="text" name="district_name" class="form-control"
                                placeholder="Enter district name" required>
                            <span class="invalid-msg"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save px-4">Save District</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editDistrictModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit District</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDistrictForm" method="POST" novalidate onsubmit="return validateDistrictForm(this)">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 text-start">
                            <label class="form-label">Select State <span class="req">*</span></label>
                            <select name="state_id" id="edit_state_id" class="form-select" required>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-msg"></span>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">District Name <span class="req">*</span></label>
                            <input type="text" name="district_name" id="edit_district_name" class="form-control" required>
                            <span class="invalid-msg"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save px-4">Update District</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.includes.footer_links')

    <script>
        // ── Search filter ──────────────────────────────────────────────
        document.getElementById('districtSearch').addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows  = document.querySelectorAll('tbody tr[data-name]');

            rows.forEach(function (row) {
                const name = row.getAttribute('data-name');
                row.style.display = (!query || name.includes(query)) ? '' : 'none';
            });

            let emptyRow = document.getElementById('noSearchResult');
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.id = 'noSearchResult';
                emptyRow.innerHTML = '<td colspan="5" class="text-center py-4 text-muted">No matching districts found.</td>';
                document.querySelector('tbody').appendChild(emptyRow);
            }
            const visible = [...rows].filter(r => r.style.display !== 'none');
            emptyRow.style.display = visible.length === 0 ? '' : 'none';
        });

        // ── Logic for Modals and Validation ─────────────────────────────
        function editDistrict(id, name, stateId) {
            document.getElementById('edit_district_name').value = name;
            document.getElementById('edit_state_id').value = stateId;
            document.getElementById('editDistrictForm').action = "{{ url('/updateDistrict') }}/" + id;
        }

        function validateDistrictForm(form) {
            let isValid = true;
            const state = form.querySelector('select[name="state_id"]');
            const district = form.querySelector('input[name="district_name"]');

            // Reset UI
            form.querySelectorAll('.invalid-msg').forEach(el => el.innerHTML = "");
            form.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));

            if (!state.value) {
                state.classList.add('is-invalid');
                state.nextElementSibling.innerHTML = '<i class="bi bi-exclamation-circle"></i> Please select a state.';
                isValid = false;
            }

            if (!district.value.trim()) {
                district.classList.add('is-invalid');
                district.nextElementSibling.innerHTML = '<i class="bi bi-exclamation-circle"></i> District name is required.';
                isValid = false;
            }

            return isValid;
        }
    </script>

</body>

</html>