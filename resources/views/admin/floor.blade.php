<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Floors</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">Floors Master Data</h1>
        <div class="action-group" style="display: flex; align-items: center; gap: 12px;">
            <div class="search-wrapper" style="position: relative;">
                <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#888; pointer-events:none;"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
                <input type="text" id="floorSearch" placeholder="Search floor..."
                    style="padding: 8px 12px 8px 32px; border: 1px solid #dde1e7; border-radius: 6px;
                           font-size: 14px; outline: none; min-width: 220px; background: #fff;
                           transition: border-color 0.2s, box-shadow 0.2s;"
                    onfocus="this.style.borderColor='#4f6ef7'; this.style.boxShadow='0 0 0 3px rgba(79,110,247,0.12)';"
                    onblur="this.style.borderColor='#dde1e7'; this.style.boxShadow='none';">
            </div>
            <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addFloorModal">
                + Add Floor
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
                        <th>Floor Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($floors as $floor)
                    <tr data-name="{{ strtolower($floor->floor_name) }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $floor->floor_name }}</strong></td>
                        <td>
                            <span class="status-badge {{ $floor->status == 1 ? 'status-active' : 'status-inactive' }}">
                                {{ $floor->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $floor->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                <button type="button" class="action-btn btn-edit me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editFloorModal{{ $floor->id }}">Edit</button>
                                <a href="{{ route('deleteFloor', $floor->id) }}"
                                   class="action-btn btn-delete text-decoration-none"
                                   onclick="return confirm('Move to trash?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editFloorModal{{ $floor->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <form action="{{ route('updateFloor', $floor->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Floor</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Floor Name <span class="req">*</span></label>
                                            <input type="text" name="floor_name" class="form-control"
                                                value="{{ $floor->floor_name }}" required
                                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-\+]/g, '');">
                                            <span class="text-danger small floor-error" style="display:none;">
                                                Must contain at least letters. Numbers, hyphens, plus signs and spaces are optional.
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $floor->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $floor->status == 0 ? 'selected' : '' }}>Inactive</option>
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
                    <tr><td colspan="5" class="text-center py-4">No floor records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addFloorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('storeFloor') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Floor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Floor Name <span class="req">*</span></label>
                    <input type="text" name="floor_name" class="form-control"
                        placeholder="e.g. 5th Floor, G+2"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-\+]/g, '');">
                    <span class="text-danger small floor-error" style="display:none;">
                        Must contain at least letters. Numbers, hyphens, plus signs and spaces are optional.
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
    document.getElementById('floorSearch').addEventListener('input', function () {
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
    function validateFloor(input) {
        const regex = /^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-\+]+$/;
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
        const input = form.querySelector('[name="floor_name"]');
        if (input) {
            const isValid = validateFloor(input);
            if (!isValid) e.preventDefault();
        }
    });
</script>

</body>
</html>