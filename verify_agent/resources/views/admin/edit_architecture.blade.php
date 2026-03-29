<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Architecture Project</title>
    @include('admin.includes.header_links')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        <form class="property-form" action="{{ route('updateArchitecture', $architecture->id) }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Project Name <span class="req">*</span></label>
                    <input type="text" class="form-control" name="project_name"
                        value="{{ old('project_name', $architecture->project_name) }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Project Type <span class="req">*</span></label>
                    <select class="form-select @error('project_type') is-invalid @enderror" name="project_type">
                        <option value="">Select type</option>
                        @foreach ($project_types as $item)
                            <option value="{{ $item->id }}" {{-- FIX: Change $property to $architecture to match your controller --}}
                                {{ old('project_type', $architecture->project_type ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->project_type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Architect / Firm Name <span class="req">*</span></label>
                    <input type="text" class="form-control" name="architect_name"
                        value="{{ old('architect_name', $architecture->architect_name) }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Project Status <span class="req">*</span></label>
                    <select class="form-select" name="project_status" required>
                        @foreach (['Concept / Design Stage', 'Under Approval', 'Approved', 'Under Construction', 'Completed'] as $status)
                            <option value="{{ $status }}"
                                {{ old('project_status', $architecture->project_status) == $status ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Submission Date <span class="req">*</span></label>
                    <input type="text" class="form-control flatpickr" name="submission_date"
                        value="{{ old('submission_date', $architecture->submission_date) }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Approval Date</label>
                    <input type="text" class="form-control flatpickr" name="approval_date"
                        value="{{ old('approval_date', $architecture->approval_date) }}" />
                </div>

                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="city"
                        value="{{ old('city', $architecture->city) }}" />
                </div>

                <div class="col-12">
                    <label class="form-label">Project Address</label>
                    <textarea class="form-control" name="project_address" rows="2">{{ old('project_address', $architecture->project_address) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Project Description</label>
                    <textarea class="form-control" name="description" rows="4">{{ old('description', $architecture->description) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Update Plans (optional)</label>
                    <input type="file" class="form-control" name="plans" accept=".pdf,.jpg,.png" />
                    @if ($architecture->plans)
                        <small class="text-success mt-1 d-block">Current file: {{ $architecture->plans }}</small>
                    @endif
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Update Project</button>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d M Y"
        });
    </script>
</body>

</html>
