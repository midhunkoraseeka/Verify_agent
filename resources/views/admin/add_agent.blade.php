<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ isset($agent) ? 'Edit Agent' : 'Add New Agent' }}</title>
    @include('admin.includes.header_links')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .is-invalid {
            border: 1px solid #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
        }

        .error-text {
            width: 100%;
            margin-top: 0.35rem;
            font-size: 0.85rem;
            color: #dc3545;
            font-weight: 500;
            display: block;
        }

        .col-md-4,
        .col-md-6,
        .col-12 {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">{{ isset($agent) ? 'Edit Agent' : 'Add New Agent' }}</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageAgent') }}" class="btn-action btn-manage">Manage Agents</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('storeAgent') }}" method="POST" id="addAgentForm"
            autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="agent_db_id" value="{{ $agent->id ?? '' }}">
            <input type="hidden" name="display_agent_id" value="{{ $nextId }}">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Agent ID</label>
                    <input type="text" class="form-control" value="{{ $nextId }}" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Priority </label>

                    <select name="priority" class="form-control @error('priority') is-invalid @enderror">
                        <option value="0" {{ old('priority', $agent->priority ?? '') === '0' ? 'selected' : '' }}>
                            High</option>
                        <option value="1" {{ old('priority', $agent->priority ?? '') === '1' ? 'selected' : '' }}>
                            Medium</option>
                        <option value="2" {{ old('priority', $agent->priority ?? '') === '2' ? 'selected' : '' }}>
                            Low</option>
                    </select>

                    @error('priority')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Full Name" name="full_name"
                        value="{{ old('full_name', $agent->agent_name ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Father Name <span class="req">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Father Name" name="father_name"
                        value="{{ old('father_name', $agent->father_name ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date of Birth <span class="req">*</span></label>
                    <input type="text" class="form-control flat-date" placeholder="Enter DOB" name="dob"
                        value="{{ old('dob', isset($agent) ? date('d-m-Y', strtotime($agent->date_of_birth)) : '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Location <span class="req">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Location" name="location"
                        value="{{ old('location', $agent->location ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Constituency <span class="req">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Constituency" name="constituency"
                        value="{{ old('constituency', $agent->constituency ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">District <span class="req">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter District" name="district"
                        value="{{ old('district', $agent->district ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="req">*</span></label>
                    <input type="tel" class="form-control @error('mobile_number') is-invalid @enderror"
                        placeholder="Enter Mobile Number" name="mobile_number"
                        value="{{ old('mobile_number', $agent->mobile_number ?? '') }}" maxlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 0 && !/^[6-9]/.test(this.value)) this.value = '';">

                    
                </div>

                <div class="col-12">
                    <label class="form-label">Address <span class="req">*</span></label>
                    <textarea class="form-control" placeholder="Enter Address" name="address" rows="2">{{ old('address', $agent->address ?? '') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">RERA Number <span class="req">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter RERA Number" name="rera_number"
                        value="{{ old('rera_number', $agent->rera_no ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Upload Aadhaar {!! !isset($agent) ? '<span class="req">*</span>' : '' !!}</label>
                    <input type="file" class="form-control" name="aadhaar_file" accept=".jpg,.jpeg,.png,.pdf">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Upload Photo {!! !isset($agent) ? '<span class="req">*</span>' : '' !!}</label>
                    <input type="file" class="form-control" name="profile_photo" accept=".jpg,.jpeg,.png">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Username <span class="req">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Username" name="username"
                        value="{{ old('username', $agent->username ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password </label>
                    <input type="password" class="form-control" placeholder="Enter Password" name="password">
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel"
                    onclick="window.location='{{ route('manageAgent') }}'">Cancel</button>
                <button type="submit" class="btn-save">{{ isset($agent) ? 'Update Agent' : 'Save Agent' }}</button>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links')

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".flat-date", {
            dateFormat: "d-m-Y",
            maxDate: "today",
            yearSelectorType: "dropdown",
            disableMobile: true
        });

        document.addEventListener("DOMContentLoaded", function() {
            const errors = @json($errors->toArray());
            Object.keys(errors).forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-text';
                    errorDiv.innerText = errors[fieldName][0];
                    const container = input.closest('.col-md-4, .col-md-6, .col-12');
                    if (container) {
                        container.appendChild(errorDiv);
                    } else {
                        input.parentNode.appendChild(errorDiv);
                    }
                    const clearFn = () => {
                        input.classList.remove('is-invalid');
                        const msg = container ? container.querySelector('.error-text') : null;
                        if (msg) msg.remove();
                    };
                    input.addEventListener('input', clearFn);
                    input.addEventListener('change', clearFn);
                }
            });
        });
    </script>
</body>

</html>
