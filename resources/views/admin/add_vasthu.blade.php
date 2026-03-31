<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Vasthu Consultant</title>

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

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545 !important;
            padding-right: 40px;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body>

    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">Add Vasthu Consultant</h1>

            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageVasthu') }}" class="btn-action btn-manage">Manage Vasthu Consultants</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('storeVasthu') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                            name="full_name" value="{{ old('full_name') }}">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
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
                            {{-- Added the validation icon to match your design standard --}}
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Constituency <span class="req">*</span></label>
                    <input type="text" class="form-control @error('constituency') is-invalid @enderror"
                        name="constituency" value="{{ old('constituency') }}">
                    @error('constituency')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">District <span class="req">*</span></label>
                    <input type="text" class="form-control @error('district') is-invalid @enderror" name="district"
                        value="{{ old('district') }}">
                    @error('district')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">State <span class="req">*</span></label>
                    <input type="text" class="form-control @error('state') is-invalid @enderror" name="state"
                        value="{{ old('state') }}">
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <div class="multi-select-dropdown">
                        <label class="form-label fw-semibold">
                            Vasthu Services <span class="req">*</span>
                        </label>

                        <div class="dropdown">
                            <button
                                class="dropdown-toggle form-control text-start multiSelectBtn @error('vasthu_services') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown">
                                {{-- Show previously selected values if validation fails, or default text --}}
                                {{ old('vasthu_services') ?: 'Select Vasthu Services' }}
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

                                {{-- Logic to check boxes based on old input --}}
                                @php $selectedServices = explode(', ', old('vasthu_services', '')); @endphp

                                {{-- Loop through dynamic master data --}}
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

                        {{-- The actual value sent to the database --}}
                        <input type="hidden" name="vasthu_services" class="multiSelectValue"
                            value="{{ old('vasthu_services') }}">

                        @error('vasthu_services')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Office Location <span class="req">*</span></label>
                    <textarea class="form-control @error('office_location') is-invalid @enderror" name="office_location" rows="3">{{ old('office_location') }}</textarea>
                    @error('office_location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Upload Aadhaar <span class="req">*</span></label>
                    <input type="file" class="form-control @error('aadhar') is-invalid @enderror" name="aadhar">
                    @error('aadhar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Profile Photo <span class="req">*</span></label>
                    <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                        name="profile_photo">
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Facebook (Optional)</label>
                    <input type="url" class="form-control @error('facebook') is-invalid @enderror"
                        name="facebook" value="{{ old('facebook') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Instagram (Optional)</label>
                    <input type="url" class="form-control @error('instagram') is-invalid @enderror"
                        name="instagram" value="{{ old('instagram') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">LinkedIn (Optional)</label>
                    <input type="url" class="form-control @error('linkedin') is-invalid @enderror"
                        name="linkedin" value="{{ old('linkedin') }}">
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Save Vasthu Consultant</button>
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
                    btn.textContent = selected.length ? selected.join(', ') : 'Select Vasthu Services';
                    selectAll.checked = selected.length === options.length;
                }

                selectAll.addEventListener('change', () => {
                    options.forEach(opt => opt.checked = selectAll.checked);
                    updateUI();
                });

                options.forEach(opt => opt.addEventListener('change', updateUI));

                if (hiddenInput.value) {
                    const values = hiddenInput.value.split(', ');
                    options.forEach(opt => {
                        if (values.includes(opt.value)) opt.checked = true;
                    });
                    updateUI();
                }
            });
        });
    </script>
</body>

</html>
