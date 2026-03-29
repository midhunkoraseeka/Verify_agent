<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Loan Agent</title>

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

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545 !important;
            padding-right: 40px;
            background-image: none;
        }

        .invalid-msg {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 4px;
            display: block;
        }

        /* File Preview Styles */
        .file-preview-box {
            margin-top: 8px;
            padding: 6px 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .preview-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }

        .preview-link {
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            color: #0d6efd;
        }
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Edit Loan Agent: {{ $agent->full_name }}</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageLoan') }}" class="btn-action btn-manage">Manage Loan Agents</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('updateLoan', $agent->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            
            <div class="row g-4">
                {{-- Full Name --}}
                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                            name="full_name" value="{{ old('full_name', $agent->full_name) }}">
                        @error('full_name')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Mobile --}}
                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                            value="{{ old('mobile', $agent->mobile) }}" maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0 && !/^[6-9]/.test(this.value)) this.value = '';">
                        @error('mobile')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Bank Name --}}
                <div class="col-md-4">
                    <label class="form-label">Bank Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                            name="bank_name" value="{{ old('bank_name', $agent->bank_name) }}">
                        @error('bank_name')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Bank Type --}}
                <div class="col-md-4">
                    <label class="form-label">Bank Type <span class="req">*</span></label>
                    <div class="input-group-container">
                        <select class="form-select @error('bank_type') is-invalid @enderror" name="bank_type">
                            <option value="0" {{ old('bank_type', $agent->bank_type) == '0' ? 'selected' : '' }}>Private</option>
                            <option value="1" {{ old('bank_type', $agent->bank_type) == '1' ? 'selected' : '' }}>Government</option>
                        </select>
                        @error('bank_type')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Loan Types (Dynamic) --}}
                <div class="col-md-4">
                    <div class="multi-select-dropdown">
                        <label class="form-label fw-semibold">Type of Loans <span class="req">*</span></label>
                        <div class="dropdown">
                            <button class="dropdown-toggle form-control text-start multiSelectBtn @error('loan_types') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown">
                                {{ old('loan_types', $agent->loan_types) ?: 'Select Loan Types' }}
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><label class="dropdown-item d-flex align-items-center gap-2 fw-semibold">
                                    <input type="checkbox" class="select-all"> Select All</label></li>
                                <li><hr class="dropdown-divider"></li>
                                @php
                                    $currentLoans = explode(', ', old('loan_types', $agent->loan_types));
                                @endphp
                                @foreach ($loan_master_types as $type)
                                    <li><label class="dropdown-item d-flex align-items-center gap-2">
                                        <input type="checkbox" class="option-checkbox" value="{{ $type->loan_type_name }}"
                                            {{ in_array($type->loan_type_name, $currentLoans) ? 'checked' : '' }}>
                                        {{ $type->loan_type_name }}
                                    </label></li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" name="loan_types" class="multiSelectValue" value="{{ old('loan_types', $agent->loan_types) }}">
                        @error('loan_types')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Office Address (Restored to Textarea size) --}}
                <div class="col-12">
                    <label class="form-label">Office Address / Location <span class="req">*</span></label>
                    <div class="input-group-container">
                        <textarea class="form-control @error('office_address') is-invalid @enderror" 
                                  name="office_address" rows="3">{{ old('office_address', $agent->office_address) }}</textarea>
                        @error('office_address')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Location Details --}}
                <div class="col-md-4">
                    <label class="form-label">Constituency <span class="req">*</span></label>
                    <input type="text" class="form-control" name="constituency" value="{{ old('constituency', $agent->constituency) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">District <span class="req">*</span></label>
                    <input type="text" class="form-control" name="district" value="{{ old('district', $agent->district) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">State <span class="req">*</span></label>
                    <input type="text" class="form-control" name="state" value="{{ old('state', $agent->state) }}">
                </div>

                {{-- Aadhaar Upload + Preview below --}}
                <div class="col-md-4">
                    <label class="form-label">Upload Aadhaar <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="file" class="form-control @error('aadhar') is-invalid @enderror" name="aadhar">
                        @error('aadhar')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                        
                        @if($agent->aadhar)
                        <div class="file-preview-box">
                            @if(Str::endsWith($agent->aadhar, '.pdf'))
                                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                            @else
                                <img src="{{ asset('uploads/loans/' . $agent->aadhar) }}" class="preview-img">
                            @endif
                            <a href="{{ asset('uploads/loans/' . $agent->aadhar) }}" target="_blank" class="preview-link">Current Aadhaar</a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Profile Photo Upload + Preview below --}}
                <div class="col-md-4">
                    <label class="form-label">Profile Photo <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" name="profile_photo">
                        @error('profile_photo')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror

                        @if($agent->profile_photo)
                        <div class="file-preview-box">
                            <img src="{{ asset('uploads/loans/' . $agent->profile_photo) }}" class="preview-img">
                            <span class="small text-muted">Current Photo</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Social Links --}}
                <div class="col-md-4">
                    <label class="form-label">Facebook</label>
                    <input type="url" class="form-control" name="facebook" value="{{ old('facebook', $agent->facebook) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Instagram</label>
                    <input type="url" class="form-control" name="instagram" value="{{ old('instagram', $agent->instagram) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">LinkedIn</label>
                    <input type="url" class="form-control" name="linkedin" value="{{ old('linkedin', $agent->linkedin) }}">
                </div>
            </div>

            <div class="form-actions mt-5 mb-5">
                <button type="button" class="btn-cancel px-4" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save px-5">Update Loan Agent</button>
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

                if (hiddenInput.value) {
                    const vals = hiddenInput.value.split(', ');
                    options.forEach(opt => { if (vals.includes(opt.value)) opt.checked = true; });
                    updateUI();
                }
            });
        });
    </script>
</body>

</html>