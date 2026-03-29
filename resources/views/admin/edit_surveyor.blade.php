<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Land Surveyor: {{ $surveyor->full_name }}</title>

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
            padding: 6px 0;
            font-size: 14px;
        }

        .multi-select-dropdown .dropdown-item {
            padding: 8px 14px;
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

        .form-control.is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-msg {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 4px;
            display: block;
        }

        .current-file-link {
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">Edit Land Surveyor</h1>

            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageSurveyor') }}" class="btn-action btn-manage">Manage Surveyors</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('updateSurveyor', $surveyor->id) }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                            name="full_name" value="{{ old('full_name', $surveyor->full_name) }}"
                            placeholder="Enter Full Name">
                        @error('full_name')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                            value="{{ old('mobile', $surveyor->mobile) }}" placeholder="Enter Mobile Number">
                        @error('mobile')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Constituency <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('constituency') is-invalid @enderror"
                            name="constituency" value="{{ old('constituency', $surveyor->constituency) }}"
                            placeholder="Enter Constituency">
                        @error('constituency')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">District <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('district') is-invalid @enderror"
                            name="district" value="{{ old('district', $surveyor->district) }}"
                            placeholder="Enter District">
                        @error('district')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">State <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('state') is-invalid @enderror" name="state"
                            value="{{ old('state', $surveyor->state) }}" placeholder="Enter State">
                        @error('state')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="multi-select-dropdown">
                        <label class="form-label fw-semibold">
                            Survey Services <span class="req">*</span>
                        </label>

                        <div class="dropdown">
                            <button
                                class="dropdown-toggle form-control text-start multiSelectBtn @error('survey_services') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown">
                                {{-- Display old input if validation failed, or the existing data, otherwise default text --}}
                                {{ old('survey_services', $surveyor->survey_services ?? '') ?: 'Select Survey Services' }}
                            </button>

                            <ul class="dropdown-menu w-100">
                                <li>
                                    <label class="dropdown-item d-flex align-items-center gap-2 fw-semibold">
                                        <input type="checkbox" class="select-all"> Select All
                                    </label>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                @php
                                    // Convert comma-separated string to array for checking checkboxes
                                    $selectedServices = explode(
                                        ', ',
                                        old('survey_services', $surveyor->survey_services ?? ''),
                                    );
                                @endphp

                                {{-- Loop through the dynamic master data objects --}}
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

                        {{-- Hidden input for form submission --}}
                        <input type="hidden" name="survey_services" class="multiSelectValue"
                            value="{{ old('survey_services', $surveyor->survey_services ?? '') }}">

                        @error('survey_services')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Office Location <span class="req">*</span></label>
                    <div class="input-group-container">
                        <textarea class="form-control @error('office_location') is-invalid @enderror" name="office_location" rows="3"
                            placeholder="Enter Location">{{ old('office_location', $surveyor->office_location) }}</textarea>
                        @error('office_location')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Upload Aadhaar (Leave blank to keep current)</label>
                    <div class="input-group-container">
                        <input type="file" class="form-control @error('aadhar') is-invalid @enderror"
                            name="aadhar">
                        @if ($surveyor->aadhar)
                            <a href="{{ asset('uploads/surveyors/' . $surveyor->aadhar) }}" target="_blank"
                                class="current-file-link">View Current Aadhar</a>
                        @endif
                        @error('aadhar')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Profile Photo (Leave blank to keep current)</label>
                    <div class="input-group-container">
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                            name="profile_photo">
                        @if ($surveyor->profile_photo)
                            <a href="{{ asset('uploads/surveyors/' . $surveyor->profile_photo) }}" target="_blank"
                                class="current-file-link">View Current Photo</a>
                        @endif
                        @error('profile_photo')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', $surveyor->status) == 1 ? 'selected' : '' }}>Active
                        </option>
                        <option value="0" {{ old('status', $surveyor->status) == 0 ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Update Surveyor</button>
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
                    btn.textContent = selected.length ? selected.join(', ') : 'Select Survey Services';
                    selectAll.checked = (selected.length === options.length && options.length > 0);
                }

                selectAll.addEventListener('change', () => {
                    options.forEach(opt => opt.checked = selectAll.checked);
                    updateUI();
                });

                options.forEach(opt => opt.addEventListener('change', updateUI));

                // Initial UI Update
                updateUI();
            });
        });
    </script>
</body>

</html>
