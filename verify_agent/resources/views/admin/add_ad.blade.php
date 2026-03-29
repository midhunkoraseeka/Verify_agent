<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Advertisement</title>
    @include('admin.includes.header_links')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
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
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Add New Advertisement</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageAds') }}" class="btn-action btn-manage">Manage Advertisements</a>
            </div>
        </div>

        <form class="property-form" action="{{ route('storeAd') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Ad Title <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control @error('ad_title') is-invalid @enderror"
                            name="ad_title" value="{{ old('ad_title') }}" placeholder="Enter ad title" />
                        @error('ad_title')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ad Type <span class="req">*</span></label>
                    <div class="input-group-container">
                        <select class="form-select @error('ad_type') is-invalid @enderror" name="ad_type">
                            <option value="">Select type</option>
                            @foreach ($adTypes as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('ad_type', $ad->ad_type ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->ad_type_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ad_type')
                            <i class="bi bi-exclamation-circle validation-icon" style="right: 25px;"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Start Date <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control flatpickr @error('start_date') is-invalid @enderror"
                            name="start_date" value="{{ old('start_date') }}" placeholder="Select start date" />
                        @error('start_date')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">End Date <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="text" class="form-control flatpickr @error('end_date') is-invalid @enderror"
                            name="end_date" value="{{ old('end_date') }}" placeholder="Select end date" />
                        @error('end_date')
                            <i class="bi bi-exclamation-circle validation-icon"></i>
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">External Link / URL (optional)</label>
                    <div class="input-group-container">
                        <input type="url" class="form-control @error('external_url') is-invalid @enderror"
                            name="external_url" value="{{ old('external_url') }}"
                            placeholder="https://example.com/property-offer" />
                        @error('external_url')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Ad Description / Text</label>
                    <textarea class="form-control" name="ad_text" rows="3" placeholder="Enter ad description">{{ old('ad_text') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ad Image / Banner <span class="req">*</span></label>
                    <div class="input-group-container">
                        <input type="file" class="form-control @error('ad_image') is-invalid @enderror"
                            name="ad_image" accept="image/*" />
                        @error('ad_image')
                            <span class="invalid-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Ad Video (optional)</label>
                    <input type="file" class="form-control" name="ad_video" accept="video/*" />
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Save</button>
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
