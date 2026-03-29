<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Advocate</title>

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

        .multi-select-dropdown .dropdown-toggle:focus {
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .25);
        }

        .multi-select-dropdown .dropdown-menu {
            max-height: 260px;
            overflow-y: auto;
            padding: 6px 0;
            font-size: 14px;
        }

        .multi-select-dropdown .dropdown-item {
            padding: 8px 14px;
            cursor: pointer;
        }

        .multi-select-dropdown .dropdown-item:hover {
            background-color: #f1f5f9;
        }

        .multi-select-dropdown input[type="checkbox"] {
            accent-color: #0d6efd;
            cursor: pointer;
        }
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')


    <div class="content">

        <div class="page-head">
            <h1 class="page-title">Add New Advocate</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageAdvocate') }}" class="btn-action btn-manage">Manage Advocates</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('storeAdvocate') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name"
                        value="{{ old('full_name') }}">
                    @error('full_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
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
                    <input type="text" class="form-control" name="constituency" value="{{ old('constituency') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">District</label>
                    <input type="text" class="form-control" name="district" value="{{ old('district') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" class="form-control" name="state" value="{{ old('state') }}">
                </div>

                <div class="col-md-4">
                    <div class="multi-select-dropdown">
                        <label class="form-label fw-semibold">
                            Legal Services (Specialized) <span class="req">*</span>
                        </label>

                        <div class="dropdown">
                            <button
                                class="dropdown-toggle form-control text-start multiSelectBtn @error('legal_services') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown">
                                Select Legal Services
                            </button>

                            <ul class="dropdown-menu w-100">
                                <li>
                                    <label class="dropdown-item d-flex align-items-center gap-2 fw-semibold">
                                        <input type="checkbox" class="select-all">
                                        Select All
                                    </label>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                {{-- Dynamic Options from Master Data --}}
                                @foreach ($services as $service)
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center gap-2">
                                            @php
                                                // Check if this ID was previously selected (for validation errors or editing)
                                                $selectedServices = explode(
                                                    ',',
                                                    old('legal_services', $data->legal_services ?? ''),
                                                );
                                            @endphp
                                            <input type="checkbox" class="option-checkbox" value="{{ $service->id }}"
                                                {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}>
                                            {{ $service->service_name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- The hidden input that actually stores the comma-separated string for the database --}}
                        <input type="hidden" name="legal_services" class="multiSelectValue"
                            value="{{ old('legal_services', $data->legal_services ?? '') }}">
                        @error('legal_services')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Office Location</label>
                    <textarea class="form-control" name="office_location" rows="3">{{ old('office_location') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Upload Aadhaar</label>
                    <input type="file" class="form-control" name="aadhar">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Profile Photo</label>
                    <input type="file" class="form-control" name="profile_photo">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Facebook</label>
                    <input type="url" class="form-control" name="facebook" value="{{ old('facebook') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Instagram</label>
                    <input type="url" class="form-control" name="instagram" value="{{ old('instagram') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">LinkedIn</label>
                    <input type="url" class="form-control" name="linkedin" value="{{ old('linkedin') }}">
                </div>

            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Save Advocate</button>
            </div>

        </form>
    </div>

    @include('admin.includes.footer_links')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.multi-select-dropdown').forEach(wrapper => {

                const btn = wrapper.querySelector('.multiSelectBtn');
                const selectAll = wrapper.querySelector('.select-all');
                const options = wrapper.querySelectorAll('.option-checkbox');
                const hiddenInput = wrapper.querySelector('.multiSelectValue');

                function updateUI() {
                    const selected = [];
                    options.forEach(opt => opt.checked && selected.push(opt.value));

                    hiddenInput.value = selected.join(', ');
                    btn.textContent = selected.length ? selected.join(', ') : 'Select Legal Services';
                    selectAll.checked = selected.length === options.length && options.length > 0;
                }

                selectAll.addEventListener('change', () => {
                    options.forEach(opt => opt.checked = selectAll.checked);
                    updateUI();
                });

                options.forEach(opt => opt.addEventListener('change', updateUI));

                // Logic to restore UI on validation errors
                if (hiddenInput.value) {
                    const selectedVals = hiddenInput.value.split(', ');
                    options.forEach(opt => {
                        if (selectedVals.includes(opt.value)) opt.checked = true;
                    });
                    updateUI();
                }
            });

        });
    </script>

</body>

</html>
