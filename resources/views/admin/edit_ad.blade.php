<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Advertisement</title>
    @include('admin.includes.header_links')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
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
            <h1 class="page-title">Edit Advertisement</h1>
            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
                <a href="{{ route('manageAds') }}" class="btn-action btn-manage">Manage Advertisements</a>
            </div>
        </div>

        {{-- Show all validation errors --}}
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

        <form class="property-form" action="{{ route('updateAd', $ad->id) }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf

            <div class="row g-3">

                {{-- Ad Title --}}
                <div class="col-md-4">
                    <label class="form-label">Ad Title <span class="req">*</span></label>
                    <input type="text" class="form-control @error('ad_title') is-invalid @enderror"
                        name="ad_title" value="{{ old('ad_title', $ad->ad_title) }}" required />
                    @error('ad_title')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Ad Type --}}
                {{-- FIX: Cast both sides to (int) to fix the "showing 1 instead of name" bug --}}
                <div class="col-md-4">
                    <label class="form-label">Ad Type <span class="req">*</span></label>
                    <select class="form-select @error('ad_type') is-invalid @enderror" name="ad_type" required>
                        <option value="">Select Ad Type</option>
                        @foreach ($adTypes as $item)
                            <option value="{{ $item->id }}"
                                {{ (int) old('ad_type', $ad->ad_type) === (int) $item->id ? 'selected' : '' }}>
                                {{ $item->ad_type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('ad_type')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Start Date --}}
                {{-- FIX: Safely format date — handle both Carbon object and raw string --}}
                <div class="col-md-4">
                    <label class="form-label">Start Date <span class="req">*</span></label>
                    <input type="text" class="form-control flatpickr @error('start_date') is-invalid @enderror"
                        name="start_date"
                        value="{{ old('start_date', $ad->start_date instanceof \Carbon\Carbon ? $ad->start_date->format('Y-m-d') : $ad->start_date) }}"
                        required />
                    @error('start_date')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- End Date --}}
                <div class="col-md-4">
                    <label class="form-label">End Date <span class="req">*</span></label>
                    <input type="text" class="form-control flatpickr @error('end_date') is-invalid @enderror"
                        name="end_date"
                        value="{{ old('end_date', $ad->end_date instanceof \Carbon\Carbon ? $ad->end_date->format('Y-m-d') : $ad->end_date) }}"
                        required />
                    @error('end_date')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- External URL --}}
                <div class="col-md-4">
                    <label class="form-label">External Link / URL (optional)</label>
                    <input type="url" class="form-control @error('external_url') is-invalid @enderror"
                        name="external_url" value="{{ old('external_url', $ad->external_url) }}"
                        placeholder="https://example.com/property-offer" />
                    @error('external_url')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Ad Description --}}
                <div class="col-md-12">
                    <label class="form-label">Ad Description / Text</label>
                    <textarea class="form-control" name="ad_text"
                        rows="3">{{ old('ad_text', $ad->ad_text) }}</textarea>
                </div>

                {{-- Ad Image --}}
                <div class="col-md-6">
                    @if ($ad->ad_image)
                        <label class="form-label">Current Ad Image</label>
                        <div class="mb-2">
                            <img src="{{ asset('uploads/ads/' . $ad->ad_image) }}" alt="Current Ad"
                                style="height: 100px; border-radius: 5px; border: 1px solid #ddd;">
                        </div>
                    @endif
                    <label class="form-label">Change Image (optional)</label>
                    <input type="file" class="form-control @error('ad_image') is-invalid @enderror"
                        name="ad_image" accept="image/*" />
                    <small class="form-text text-muted mt-1">Leave blank to keep existing image.</small>
                    @error('ad_image')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Ad Video --}}
                <div class="col-md-6">
                    @if ($ad->ad_video)
                        <label class="form-label">Current Video</label>
                        <div class="mb-2">
                            <span class="badge bg-secondary">Video Uploaded</span>
                            &nbsp;
                            <a href="{{ asset('uploads/ads/' . $ad->ad_video) }}" target="_blank"
                                class="small text-primary">Preview</a>
                        </div>
                    @endif
                    <label class="form-label">Change Video (optional)</label>
                    <input type="file" class="form-control @error('ad_video') is-invalid @enderror"
                        name="ad_video" accept="video/*" />
                    <small class="form-text text-muted mt-1">Leave blank to keep existing video.</small>
                    @error('ad_video')
                        <span class="invalid-msg">{{ $message }}</span>
                    @enderror
                </div>

            </div>{{-- end .row --}}

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">Cancel</button>
                <button type="submit" class="btn-save">Update Advertisement</button>
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