<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Advocate</title>
    @include('admin.includes.header_links')
    <style>
        .multi-select-dropdown .dropdown-toggle {
            height: 44px;
            border: 1px solid #ced4da;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
        }

        .multi-select-dropdown .dropdown-menu {
            max-height: 260px;
            overflow-y: auto;
            font-size: 14px;
        }

        .input-group-container {
            position: relative;
        }

        .validation-icon {
            position: absolute;
            right: 12px;
            top: 10px;
            color: #dc3545;
            font-size: 1.1rem;
            pointer-events: none;
        }
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Edit Advocate</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageAdvocate') }}" class="btn-action btn-manage">Manage Advocates</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('updateAdvocate', $advocate->id) }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                            name="full_name" value="{{ old('full_name', $advocate->full_name) }}" required>
                        @error('full_name')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                            value="{{ old('mobile') }}" placeholder="Enter 10-digit Mobile Number" maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0 && !/^[6-9]/.test(this.value)) this.value = '';">

                        @error('mobile')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Constituency</label>
                    <input type="text" class="form-control" name="constituency"
                        value="{{ old('constituency', $advocate->constituency) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">District</label>
                    <input type="text" class="form-control" name="district"
                        value="{{ old('district', $advocate->district) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" class="form-control" name="state"
                        value="{{ old('state', $advocate->state) }}">
                </div>

                <div class="col-md-4">
                    <div class="multi-select-dropdown">
                        <label class="form-label fw-semibold">Legal Services <span class="req">*</span></label>
                        <div class="dropdown">
                            <button
                                class="dropdown-toggle form-control text-start multiSelectBtn @error('legal_services') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown">
                                Select Legal Services
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="select-all"> Select All
                                    </label>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                @php
                                    // Convert the stored comma-separated string into an array for comparison
                                    $selectedServices = explode(
                                        ', ',
                                        old('legal_services', $advocate->legal_services ?? ''),
                                    );
                                @endphp

                                @foreach ($services as $service)
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center gap-2">
                                            <input type="checkbox" class="option-checkbox"
                                                value="{{ $service->service_name }}"
                                                {{ in_array($service->service_name, $selectedServices) ? 'checked' : '' }}>
                                            {{ $service->service_name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        {{-- Hidden input to store selected values for database submission --}}
                        <input type="hidden" name="legal_services" class="multiSelectValue"
                            value="{{ old('legal_services', $advocate->legal_services ?? '') }}">
                        @error('legal_services')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Office Location</label>
                    <textarea class="form-control" name="office_location" rows="3">{{ old('office_location', $advocate->office_location) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Update Aadhaar (Leave blank to keep current)</label>
                    <input type="file" class="form-control" name="aadhar">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Update Photo (Leave blank to keep current)</label>
                    <input type="file" class="form-control" name="profile_photo">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Facebook</label>
                    <input type="url" class="form-control" name="facebook"
                        value="{{ old('facebook', $advocate->facebook) }}">
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Update Advocate</button>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.multi-select-dropdown').forEach(wrapper => {
                const btn = wrapper.querySelector('.multiSelectBtn');
                const options = wrapper.querySelectorAll('.option-checkbox');
                const hiddenInput = wrapper.querySelector('.multiSelectValue');
                const selectAll = wrapper.querySelector('.select-all');

                function updateUI() {
                    const selected = [];
                    options.forEach(opt => {
                        if (opt.checked) selected.push(opt.value);
                    });
                    hiddenInput.value = selected.join(', ');
                    btn.textContent = selected.length ? selected.join(', ') : 'Select Legal Services';
                    selectAll.checked = (selected.length === options.length);
                }

                selectAll.addEventListener('change', () => {
                    options.forEach(opt => opt.checked = selectAll.checked);
                    updateUI();
                });

                options.forEach(opt => opt.addEventListener('change', updateUI));
                updateUI(); // Initialize on load
            });
        });
    </script>
</body>

</html>
