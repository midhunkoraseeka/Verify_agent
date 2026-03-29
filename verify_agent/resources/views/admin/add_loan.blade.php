<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Loan Agent</title>

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

        /* Logic for the validation UI shown in the images */
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
            /* Space for icon */
            background-image: none;
            /* Remove default bootstrap icon */
        }

        .invalid-msg {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 4px;
            display: block;
        }
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">Add Loan Agent</h1>

            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageLoan') }}" class="btn-action btn-manage">Manage Loan Agents</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('storeLoan') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                            name="full_name" value="{{ old('full_name') }}" placeholder="Enter Full Name">
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
                            value="{{ old('mobile') }}" placeholder="Enter 10-digit Mobile Number" maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0 && !/^[6-9]/.test(this.value)) this.value = '';">

                        @error('mobile')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bank Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                            name="bank_name" value="{{ old('bank_name') }}" placeholder="Enter Bank Name">
                        @error('bank_name')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bank Type <span class="req">*</span></label>
                    <div class="input-group-container">
                        <select class="form-select @error('bank_type') is-invalid @enderror" name="bank_type">
                            <option value="">Select Bank Type</option>
                            <option value="0" {{ old('bank_type') == '0' ? 'selected' : '' }}>Private</option>
                            <option value="1" {{ old('bank_type') == '1' ? 'selected' : '' }}>Government</option>
                        </select>
                        @error('bank_type')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Constituency <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('constituency') is-invalid @enderror"
                            name="constituency" value="{{ old('constituency') }}" placeholder="Enter Constituency">
                        @error('constituency')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">District <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('district') is-invalid @enderror"
                            name="district" value="{{ old('district') }}" placeholder="Enter District">
                        @error('district')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">State <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('state') is-invalid @enderror" name="state"
                            value="{{ old('state') }}" placeholder="Enter State">
                        @error('state')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="multi-select-dropdown">
                        <label class="form-label fw-semibold">
                            Type of Loans <span class="req">*</span>
                        </label>

                        <div class="dropdown">
                            <button
                                class="dropdown-toggle form-control text-start multiSelectBtn @error('loan_types') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown">
                                {{-- Display old input if validation failed, or the existing agent data, otherwise default text --}}
                                {{ old('loan_types', $agent->loan_types ?? '') ?: 'Select Loan Types' }}
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

                                @php
                                    // Convert the comma-separated string into an array for comparison
                                    $selectedLoans = explode(', ', old('loan_types', $agent->loan_types ?? ''));
                                @endphp

                                {{-- Loop through the dynamic master data from your database --}}
                                @foreach ($loan_master_types as $type)
                                    <li>
                                        <label class="dropdown-item d-flex align-items-center gap-2">
                                            <input type="checkbox" class="option-checkbox"
                                                value="{{ $type->loan_type_name }}"
                                                {{ in_array($type->loan_type_name, $selectedLoans) ? 'checked' : '' }}>
                                            {{ $type->loan_type_name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Hidden input used by JS to store the comma-separated string for form submission --}}
                        <input type="hidden" name="loan_types" class="multiSelectValue"
                            value="{{ old('loan_types', $agent->loan_types ?? '') }}">

                        @error('loan_types')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Office Address / Location <span class="req">*</span></label>
                    <div class="input-group-container">
                        <textarea class="form-control @error('office_address') is-invalid @enderror" name="office_address" rows="3">{{ old('office_address') }}</textarea>
                        @error('office_address')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Upload Aadhaar <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="file" class="form-control @error('aadhar') is-invalid @enderror"
                            name="aadhar">
                        @error('aadhar')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Profile Photo <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                            name="profile_photo">
                        @error('profile_photo')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Facebook (Optional)</label>
                    <input type="url" class="form-control" name="facebook" value="{{ old('facebook') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Instagram (Optional)</label>
                    <input type="url" class="form-control" name="instagram" value="{{ old('instagram') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">LinkedIn (Optional)</label>
                    <input type="url" class="form-control" name="linkedin" value="{{ old('linkedin') }}">
                </div>

            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Save Loan Agent</button>
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
                    btn.textContent = selected.length ? selected.join(', ') : 'Select Loan Types';
                    selectAll.checked = (selected.length === options.length && options.length > 0);
                }

                selectAll.addEventListener('change', () => {
                    options.forEach(opt => opt.checked = selectAll.checked);
                    updateUI();
                });

                options.forEach(opt => opt.addEventListener('change', updateUI));

                // Initial UI check if old data exists
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
