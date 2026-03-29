<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Parking</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">Parking Master Data</h1>
        <div class="action-group" style="display: flex; align-items: center; gap: 12px;">
            <div class="search-wrapper" style="position: relative;">
                <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#888; pointer-events:none;"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
                <input type="text" id="parkingSearch" placeholder="Search parking..."
                    style="padding: 8px 12px 8px 32px; border: 1px solid #dde1e7; border-radius: 6px;
                           font-size: 14px; outline: none; min-width: 220px; background: #fff;
                           transition: border-color 0.2s, box-shadow 0.2s;"
                    onfocus="this.style.borderColor='#4f6ef7'; this.style.boxShadow='0 0 0 3px rgba(79,110,247,0.12)';"
                    onblur="this.style.borderColor='#dde1e7'; this.style.boxShadow='none';">
            </div>
            <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addParkingModal">
                + Add Parking
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
                        <th>Parking Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parkings as $parking)
                    <tr data-name="{{ strtolower($parking->parking_name) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $parking->parking_name }}</strong></td>
                        <td>
                            <span class="status-badge {{ $parking->status == 1 ? 'status-active' : 'status-inactive' }}">
                                {{ $parking->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $parking->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                <button type="button" class="action-btn btn-edit me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editParkingModal{{ $parking->id }}">Edit</button>
                                <a href="{{ route('deleteParking', $parking->id) }}"
                                   class="action-btn btn-delete text-decoration-none"
                                   onclick="return confirm('Delete this record?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editParkingModal{{ $parking->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <form action="{{ route('updateParking', $parking->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Parking</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Parking Name <span class="req">*</span></label>
                                            <input type="text" name="parking_name" class="form-control"
                                                value="{{ $parking->parking_name }}" required
                                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-]/g, '');">
                                            <span class="text-danger small parking-error" style="display:none;">
                                                Must contain at least letters. Numbers, hyphens and spaces are optional.
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $parking->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $parking->status == 0 ? 'selected' : '' }}>Inactive</option>
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
<div class="modal fade" id="addParkingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('storeParking') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Parking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Parking Name <span class="req">*</span></label>
                    <input type="text" name="parking_name" class="form-control"
                        placeholder="e.g. 2 Wheeler, 4 Wheeler"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-]/g, '');">
                    <span class="text-danger small parking-error" style="display:none;">
                        Must contain at least letters. Numbers, hyphens and spaces are optional.
                    </span>
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

<script>
    // ── Search filter ──────────────────────────────────────────────
    document.getElementById('parkingSearch').addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows  = document.querySelectorAll('tbody tr[data-name]');

        rows.forEach(function (row) {
            const name = row.getAttribute('data-name');
            row.style.display = (!query || name.includes(query)) ? '' : 'none';
        });

        // "no results" feedback row
        let emptyRow = document.getElementById('noSearchResult');
        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.id = 'noSearchResult';
            emptyRow.innerHTML = '<td colspan="5" class="text-center py-4 text-muted">No matching records found.</td>';
            document.querySelector('tbody').appendChild(emptyRow);
        }
        const visible = [...rows].filter(r => r.style.display !== 'none');
        emptyRow.style.display = visible.length === 0 ? '' : 'none';
    });

    // ── Form validation ────────────────────────────────────────────
    function validateParking(input) {
        const regex = /^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/;
        const errorSpan = input.nextElementSibling;

        if (!regex.test(input.value.trim())) {
            errorSpan.style.display = 'block';
            return false;
        } else {
            errorSpan.style.display = 'none';
            return true;
        }
    }

    document.addEventListener('submit', function (e) {
        const form  = e.target;
        const input = form.querySelector('[name="parking_name"]');
        if (input) {
            const isValid = validateParking(input);
            if (!isValid) e.preventDefault();
        }
    });
</script>

</body>
</html>