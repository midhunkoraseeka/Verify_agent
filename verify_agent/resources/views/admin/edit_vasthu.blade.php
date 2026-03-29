<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Vasthu Consultant</title>

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

        /* File Preview Styles directly below inputs */
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
            <h1 class="page-title">Edit Vasthu Consultant: {{ $consultant->full_name }}</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageVasthu') }}" class="btn-action btn-manage">Manage Consultants</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('updateVasthu', $consultant->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            
            <div class="row g-4">
                {{-- Full Name --}}
                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                            name="full_name" value="{{ old('full_name', $consultant->full_name) }}">
                        @error('full_name')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Mobile Number --}}
                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                            value="{{ old('mobile', $consultant->mobile) }}" placeholder="Enter 10-digit Mobile Number"
                            maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0 && !/^[6-9]/.test(this.value)) this.value = '';">
                        @error('mobile')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Vasthu Services (Dynamic Multi-select) --}}
                <div class="col-md-4">
                    <div class="multi-select-dropdown">
                        <label class="form-label fw-semibold">Vasthu Services <span class="req">*</span></label>
                        <div class="dropdown">
                            <button class="dropdown-toggle form-control text-start multiSelectBtn @error('vasthu_services') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown">
                                {{ old('vasthu_services', $consultant->vasthu_services) ?: 'Select Vasthu Services' }}
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><label class="dropdown-item d-flex align-items-center gap-2 fw-semibold">
                                    <input type="checkbox" class="select-all"> Select All</label></li>
                                <li><hr class="dropdown-divider"></li>
                                @php
                                    $selectedArr = explode(', ', old('vasthu_services', $consultant->vasthu_services));
                                @endphp
                                @foreach ($services as $service)
                                    <li><label class="dropdown-item d-flex align-items-center gap-2">
                                        <input type="checkbox" class="option-checkbox" value="{{ $service->service_name }}"
                                            {{ in_array($service->service_name, $selectedArr) ? 'checked' : '' }}>
                                        {{ $service->service_name }}
                                    </label></li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" name="vasthu_services" class="multiSelectValue" value="{{ old('vasthu_services', $consultant->vasthu_services) }}">
                        @error('vasthu_services')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Constituency --}}
                <div class="col-md-4">
                    <label class="form-label">Constituency <span class="req">*</span></label>
                    <input type="text" class="form-control @error('constituency') is-invalid @enderror"
                        name="constituency" value="{{ old('constituency', $consultant->constituency) }}">
                    @error('constituency') <span class="invalid-msg">{{ $message }}</span> @enderror
                </div>

                {{-- District --}}
                <div class="col-md-4">
                    <label class="form-label">District <span class="req">*</span></label>
                    <input type="text" class="form-control @error('district') is-invalid @enderror" name="district"
                        value="{{ old('district', $consultant->district) }}">
                    @error('district') <span class="invalid-msg">{{ $message }}</span> @enderror
                </div>

                {{-- State --}}
                <div class="col-md-4">
                    <label class="form-label">State <span class="req">*</span></label>
                    <input type="text" class="form-control @error('state') is-invalid @enderror" name="state"
                        value="{{ old('state', $consultant->state) }}">
                    @error('state') <span class="invalid-msg">{{ $message }}</span> @enderror
                </div>

                {{-- Office Location --}}
                <div class="col-12">
                    <label class="form-label">Office Location <span class="req">*</span></label>
                    <textarea class="form-control @error('office_location') is-invalid @enderror" name="office_location" rows="3">{{ old('office_location', $consultant->office_location) }}</textarea>
                    @error('office_location') <span class="invalid-msg">{{ $message }}</span> @enderror
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
                        
                        @if($consultant->aadhar)
                        <div class="file-preview-box">
                            @if(Str::endsWith($consultant->aadhar, '.pdf'))
                                <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                            @else
                                <img src="{{ asset('uploads/vasthu/' . $consultant->aadhar) }}" class="preview-img">
                            @endif
                            <a href="{{ asset('uploads/vasthu/' . $consultant->aadhar) }}" target="_blank" class="preview-link">Current Aadhaar</a>
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

                        @if($consultant->profile_photo)
                        <div class="file-preview-box">
                            <img src="{{ asset('uploads/vasthu/' . $consultant->profile_photo) }}" class="preview-img">
                            <span class="small text-muted">Current Photo</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Social Media Links --}}
                <div class="col-md-4">
                    <label class="form-label">Facebook</label>
                    <input type="url" class="form-control" name="facebook" value="{{ old('facebook', $consultant->facebook) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Instagram</label>
                    <input type="url" class="form-control" name="instagram" value="{{ old('instagram', $consultant->instagram) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">LinkedIn</label>
                    <input type="url" class="form-control" name="linkedin" value="{{ old('linkedin', $consultant->linkedin) }}">
                </div>
            </div>

            <div class="form-actions mt-5 mb-5">
                <button type="button" class="btn-cancel px-4" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save px-5">Update Consultant</button>
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