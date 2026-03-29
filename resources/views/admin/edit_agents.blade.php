<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Agent</title>
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
            <h1 class="page-title">Agent Name: {{ $agent->agent_name }}</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageAgent') }}" class="btn-action btn-manage">Manage Agents</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('updateAgent', $agent->id) }}" method="POST" id="editAgentForm"
            autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="agent_db_id" value="{{ $agent->id }}">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Agent ID</label>
                    <input type="text" class="form-control" value="{{ $agent->agent_id }}" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Priority <span class="req">*</span></label>
                    <select class="form-control" name="priority">
                        <option value="0" {{ old('priority', $agent->priority) == 0 ? 'selected' : '' }}>High</option>
                        <option value="1" {{ old('priority', $agent->priority) == 1 ? 'selected' : '' }}>Medium</option>
                        <option value="2" {{ old('priority', $agent->priority) == 2 ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <input type="text" class="form-control" name="full_name"
                        value="{{ old('full_name', $agent->agent_name) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Father Name <span class="req">*</span></label>
                    <input type="text" class="form-control" name="father_name"
                        value="{{ old('father_name', $agent->father_name) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date of Birth <span class="req">*</span></label>
                    <input type="text" class="form-control flat-date" name="dob"
                        value="{{ old('dob', date('d-m-Y', strtotime($agent->date_of_birth))) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Location <span class="req">*</span></label>
                    <input type="text" class="form-control" name="location"
                        value="{{ old('location', $agent->location) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Constituency <span class="req">*</span></label>
                    <input type="text" class="form-control" name="constituency"
                        value="{{ old('constituency', $agent->constituency) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">District <span class="req">*</span></label>
                    <input type="text" class="form-control" name="district"
                        value="{{ old('district', $agent->district) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mobile Number <span class="req">*</span></label>
                    <input type="tel" class="form-control" name="mobile_number" maxlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"
                        value="{{ old('mobile_number', $agent->mobile_number) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Address <span class="req">*</span></label>
                    <textarea class="form-control" name="address" rows="2">{{ old('address', $agent->address) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">RERA Number <span class="req">*</span></label>
                    <input type="text" class="form-control" name="rera_number"
                        value="{{ old('rera_number', $agent->rera_no) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Update Aadhaar</label>
                    <input type="file" class="form-control" name="aadhaar_file" accept=".jpg,.jpeg,.png,.pdf" onchange="previewAadhaar(this)">
                    <div class="mt-2" id="aadhaarPreview">
                        @if ($agent->agent_aadhar)
                            @if (Str::endsWith($agent->agent_aadhar, '.pdf'))
                                <a href="{{ asset('uploads/agents/' . $agent->agent_aadhar) }}" target="_blank">📄 View Existing Aadhaar</a>
                            @else
                                <img src="{{ asset('uploads/agents/' . $agent->agent_aadhar) }}" style="max-width:100px;border:1px solid #ccc;padding:4px;">
                            @endif
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Update Photo</label>
                    <input type="file" class="form-control" name="profile_photo" accept=".jpg,.jpeg,.png" onchange="previewPhoto(this)">
                    <div class="mt-2">
                        <img id="photoPreview" src="{{ $agent->agent_photo ? asset('uploads/agents/' . $agent->agent_photo) : '' }}" 
                             style="max-width:100px; {{ $agent->agent_photo ? '' : 'display:none;' }} border:1px solid #ccc;padding:4px;">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Username <span class="req">*</span></label>
                    <input type="text" class="form-control" name="username"
                        value="{{ old('username', $agent->username) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password (Leave blank to keep current)</label>
                    <input type="password" class="form-control" name="password">
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="window.location='{{ route('manageAgent') }}'">Cancel</button>
                <button type="submit" class="btn-save">Update Agent</button>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links')

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".flat-date", { dateFormat: "d-m-Y", maxDate: "today", yearSelectorType: "dropdown", disableMobile: true });

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

        function previewPhoto(input) {
            const preview = document.getElementById('photoPreview');
            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.style.display = 'block';
            }
        }

        function previewAadhaar(input) {
            const previewDiv = document.getElementById('aadhaarPreview');
            previewDiv.innerHTML = '';
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.type === 'application/pdf') {
                    previewDiv.innerHTML = `<p>📄 Selected: <strong>${file.name}</strong></p>`;
                } else {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '100px'; img.style.border = '1px solid #ccc'; img.style.padding = '4px';
                    previewDiv.appendChild(img);
                }
            }
        }
    </script>
</body>
</html>