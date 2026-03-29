<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Architecture Project</title>
    @include('admin.includes.header_links')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .hidden {
            display: none !important;
        }
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Edit Architecture Project</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageArchitecture') }}" class="btn-action btn-manage">Manage Architectures</a>
            </div>
        </div>

        {{-- Show all validation errors clearly --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        <form class="property-form" action="{{ route('updateArchitecture', $architecture->id) }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- Project Name --}}
                <div class="col-md-4">
                    <label class="form-label">Project Name <span class="req">*</span></label>
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror"
                        name="project_name"
                        value="{{ old('project_name', $architecture->project_name) }}" required />
                    @error('project_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Project Type --}}
                <div class="col-md-4">
                    <label class="form-label">Project Type <span class="req">*</span></label>
                    <select class="form-select @error('project_type') is-invalid @enderror" name="project_type"
                        id="projectTypeSelect" required>
                        <option value="">Select type</option>
                        @foreach ($project_types as $item)
                            <option value="{{ $item->id }}"
                                {{ old('project_type', $architecture->project_type) == $item->id ? 'selected' : '' }}>
                                {{ $item->project_type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Architect Name --}}
                <div class="col-md-4">
                    <label class="form-label">Architect / Firm Name <span class="req">*</span></label>
                    <input type="text" class="form-control @error('architect_name') is-invalid @enderror"
                        name="architect_name"
                        value="{{ old('architect_name', $architecture->architect_name) }}" required />
                    @error('architect_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- License No --}}
                <div class="col-md-4">
                    <label class="form-label">Architect License / Registration No</label>
                    <input type="text" class="form-control" name="license_no"
                        value="{{ old('license_no', $architecture->license_no) }}" placeholder="COA-56789" />
                </div>

                {{-- Project Status --}}
                <div class="col-md-4">
                    <label class="form-label">Project Status <span class="req">*</span></label>
                    <select class="form-select @error('project_status') is-invalid @enderror" name="project_status" required>
                        <option value="">Select status</option>
                        @foreach (['Concept / Design Stage', 'Under Approval', 'Approved', 'Under Construction', 'Completed'] as $status)
                            <option value="{{ $status }}"
                                {{ old('project_status', $architecture->project_status) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_status')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submission Date --}}
                <div class="col-md-4">
                    <label class="form-label">Submission Date <span class="req">*</span></label>
                    <input type="text" class="form-control flatpickr @error('submission_date') is-invalid @enderror"
                        name="submission_date"
                        value="{{ old('submission_date', $architecture->submission_date) }}" required />
                    @error('submission_date')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Approval Date --}}
                <div class="col-md-4">
                    <label class="form-label">Approval Date</label>
                    <input type="text" class="form-control flatpickr" name="approval_date"
                        value="{{ old('approval_date', $architecture->approval_date) }}" />
                </div>

                {{-- City --}}
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="city"
                        value="{{ old('city', $architecture->city) }}" placeholder="Bangalore" />
                </div>

                {{-- State --}}
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" class="form-control" name="state"
                        value="{{ old('state', $architecture->state) }}" placeholder="Karnataka" />
                </div>

                {{-- PIN Code --}}
                <div class="col-md-4">
                    <label class="form-label">PIN Code</label>
                    <input type="text" class="form-control" name="pincode"
                        value="{{ old('pincode', $architecture->pincode) }}" placeholder="560066" />
                </div>

                {{-- Project Address --}}
                <div class="col-12">
                    <label class="form-label">Project Address</label>
                    <textarea class="form-control" name="project_address" rows="2">{{ old('project_address', $architecture->project_address) }}</textarea>
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">Project Description</label>
                    <textarea class="form-control" name="description" rows="4">{{ old('description', $architecture->description) }}</textarea>
                </div>

                {{-- File Upload --}}
                <div class="col-12">
                    <label class="form-label">Update Plans (optional)</label>
                    <input type="file" class="form-control @error('plans') is-invalid @enderror"
                        name="plans" accept=".pdf,.jpg,.png" />
                    <small class="form-text text-muted mt-1">PDF, JPG, PNG (max 10MB). Leave blank to keep existing file.</small>
                    @if ($architecture->plans)
                        <small class="text-success mt-1 d-block">
                            Current file:
                            <a href="{{ asset('uploads/architectures/' . $architecture->plans) }}" target="_blank">
                                {{ $architecture->plans }}
                            </a>
                        </small>
                    @endif
                    @error('plans')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

            </div>{{-- end .row --}}

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Update Project</button>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links')

    <script>
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d M Y",
            allowInput: true
        });
    </script>
</body>

</html>