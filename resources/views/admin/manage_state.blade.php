<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage States</title>
    @include('admin.includes.header_links')
</head>

<body>

    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">State Master Data</h1>
            <div class="action-group" style="display: flex; align-items: center; gap: 12px;">
                <div class="search-wrapper" style="position: relative;">
                    <svg style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#888; pointer-events:none;"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                    </svg>
                    <input type="text" id="stateSearch" placeholder="Search state..."
                        style="padding: 8px 12px 8px 32px; border: 1px solid #dde1e7; border-radius: 6px;
                               font-size: 14px; outline: none; min-width: 220px; background: #fff;
                               transition: border-color 0.2s, box-shadow 0.2s;"
                        onfocus="this.style.borderColor='#4f6ef7'; this.style.boxShadow='0 0 0 3px rgba(79,110,247,0.12)';"
                        onblur="this.style.borderColor='#dde1e7'; this.style.boxShadow='none';">
                </div>
                <button type="button" class="btn-action btn-manage" data-bs-toggle="modal"
                    data-bs-target="#addStateModal">
                    + Add New State
                </button>
            </div>
        </div>

        <div class="property-form">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
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
                            <th>State Name</th>
                            <th>Status</th>
                            <th>Added On</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($states as $state)
                            <tr data-name="{{ strtolower($state->state_name) }}">
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $state->state_name }}</strong></td>
                                <td>
                                    @if ($state->status == 1)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $state->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <div class="action-cell justify-content-end">
                                        <button type="button" class="action-btn btn-edit me-2" data-bs-toggle="modal"
                                            data-bs-target="#editStateModal{{ $state->id }}">
                                            Edit
                                        </button>

                                        <a href="{{ route('deleteState', $state->id) }}"
                                            class="action-btn btn-delete text-decoration-none"
                                            onclick="return confirm('Move this state to trash?')">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            {{-- DYNAMIC EDIT MODAL --}}
                            <div class="modal fade" id="editStateModal{{ $state->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content text-start">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit State</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('updateState', $state->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">State Name <span
                                                            class="req">*</span></label>
                                                    <input type="text" name="state_name" class="form-control"
                                                        value="{{ $state->state_name }}" required
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');">
                                                    <span class="text-danger small state-error" style="display:none;">
                                                        State name is required.
                                                    </span>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="1" {{ $state->status == 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ $state->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn-save px-4">Update State</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No state data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addStateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New State</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('storeState') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">State Name <span class="req">*</span></label>
                            <input type="text" name="state_name" class="form-control"
                                placeholder="e.g. Telangana, Andhra Pradesh"
                                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '');">
                            <span class="text-danger small state-error" style="display:none;">
                               State name is required.
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save px-4">Save State</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.includes.footer_links')

    <script>
        // ── Search filter ──────────────────────────────────────────────
        document.getElementById('stateSearch').addEventListener('input', function () {
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
                emptyRow.innerHTML = '<td colspan="5" class="text-center py-4 text-muted">No matching records found.</td>';
                document.querySelector('tbody').appendChild(emptyRow);
            }
            const visible = [...rows].filter(r => r.style.display !== 'none');
            emptyRow.style.display = visible.length === 0 ? '' : 'none';
        });

        // ── Form validation ────────────────────────────────────────────
        function validateState(input) {
            const errorSpan = input.nextElementSibling;
            if (input.value.trim() === "") {
                errorSpan.style.display = 'block';
                input.classList.add('is-invalid');
                return false;
            } else {
                errorSpan.style.display = 'none';
                input.classList.remove('is-invalid');
                return true;
            }
        }

        document.addEventListener('submit', function (e) {
            const form  = e.target;
            const input = form.querySelector('[name="state_name"]');
            if (input) {
                const isValid = validateState(input);
                if (!isValid) e.preventDefault();
            }
        });
    </script>

</body>

</html>