<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Architecture</title>
    @include('admin.includes.header_links')

    <!-- Flatpickr CSS & JS for date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .hidden {
            display: none !important;
        }
    </style>
</head>

<body>
    @include('admin.includes.sidebar') ?>
    @include('admin.includes.header') ?>

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Add New Architecture</h1>

            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageArchitecture') }}" class="btn-action btn-manage">
                    Manage Architectures
                </a>
            </div>
        </div>

        <form class="property-form" id="addArchitectureForm" action="{{ route('storeArchitecture') }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Architecture / Project Name <span class="req">*</span></label>
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror"
                        name="project_name" value="{{ old('project_name') }}" placeholder="Elite Heights Tower" />
                    @error('project_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Project Type <span class="req">*</span></label>
                    <select class="form-select @error('project_type') is-invalid @enderror" name="project_type"
                        id="projectTypeSelect">
                        <option value="">Select type</option>
                        @foreach ($project_types as $item)
                            <option value="{{ $item->id }}"
                                {{ old('project_type', $architecture->project_type ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->project_type_name }}
                            </option>
                        @endforeach
                        <option value="other"
                            {{ old('project_type', $architecture->project_type ?? '') == 'other' ? 'selected' : '' }}>
                            Other</option>
                    </select>
                    @error('project_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- This div will appear next to or below depending on your column width --}}
                <div class="col-md-4 {{ old('project_type', $architecture->project_type ?? '') == 'other' ? '' : 'hidden' }}"
                    id="otherProjectTypeDiv">
                    <label class="form-label">Specify Other Type <span class="req">*</span></label>
                    <input type="text" name="other_project_type"
                        class="form-control @error('other_project_type') is-invalid @enderror"
                        value="{{ old('other_project_type', $architecture->other_project_type ?? '') }}"
                        placeholder="Enter project type">
                    @error('other_project_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-4">
                    <label class="form-label">Architect / Firm Name <span class="req">*</span></label>
                    <input type="text" class="form-control @error('architect_name') is-invalid @enderror"
                        name="architect_name" value="{{ old('architect_name') }}" placeholder="Studio Axis Designs" />
                    @error('architect_name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Architect License / Registration No</label>
                    <input type="text" class="form-control" name="license_no" value="{{ old('license_no') }}"
                        placeholder="COA-56789" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Project Status <span class="req">*</span></label>
                    <select class="form-select @error('project_status') is-invalid @enderror" name="project_status">
                        <option value="">Select status</option>
                        @foreach (['Concept / Design Stage', 'Under Approval', 'Approved', 'Under Construction', 'Completed'] as $status)
                            <option value="{{ $status }}"
                                {{ old('project_status') == $status ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                    @error('project_status')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Submission Date <span class="req">*</span></label>
                    <input type="text" class="form-control flatpickr @error('submission_date') is-invalid @enderror"
                        name="submission_date" value="{{ old('submission_date') }}" placeholder="Select date" />
                    @error('submission_date')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Approval Date</label>
                    <input type="text" class="form-control flatpickr" name="approval_date"
                        value="{{ old('approval_date') }}" placeholder="Select date" />
                </div>

                <div class="col-12">
                    <label class="form-label">Project Address / Location</label>
                    <textarea class="form-control" name="project_address" rows="2"
                        placeholder="Survey No. 128, Green Valley Layout...">{{ old('project_address') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="city" value="{{ old('city') }}"
                        placeholder="Bangalore" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" class="form-control" name="state" value="{{ old('state') }}"
                        placeholder="Karnataka" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">PIN Code</label>
                    <input type="text" class="form-control" name="pincode" value="{{ old('pincode') }}"
                        placeholder="560066" />
                </div>

                <div class="col-12">
                    <label class="form-label">Project Description / Highlights</label>
                    <textarea class="form-control" name="description" rows="4"
                        placeholder="Modern 15-storey residential tower...">{{ old('description') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Upload Drawings / Plans (optional)</label>
                    <input type="file" class="form-control @error('plans') is-invalid @enderror" name="plans"
                        accept=".pdf,.jpg,.png" />
                    <small class="form-text text-muted mt-1">PDF, JPG, PNG (max 10MB per file)</small>
                    @error('plans')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Save Architecture</button>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links') ?>

    <!-- Flatpickr Initialization -->
    <script>
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            enableTime: false,
            altInput: true,
            altFormat: "d M Y",
            allowInput: true
        });


        document.addEventListener('DOMContentLoaded', function() {
            const projectTypeSelect = document.getElementById('projectTypeSelect');
            const otherDiv = document.getElementById('otherProjectTypeDiv');

            if (projectTypeSelect) {
                projectTypeSelect.addEventListener('change', function() {
                    // Check if value is exactly 'other'
                    if (this.value === 'other') {
                        otherDiv.classList.remove('hidden');
                    } else {
                        otherDiv.classList.add('hidden');
                        // Clear input so junk data isn't saved accidentally
                        const input = otherDiv.querySelector('input');
                        if (input) input.value = '';
                    }
                });
            }
        });
    </script>
</body>

</html>
