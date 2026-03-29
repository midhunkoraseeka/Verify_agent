<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Advertisement</title>
    @include('admin.includes.header_links')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="property-form" action="{{ route('updateAd', $ad->id) }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Ad Title <span class="req">*</span></label>
                    <input type="text" class="form-control" name="ad_title"
                        value="{{ old('ad_title', $ad->ad_title) }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ad Type <span class="req">*</span></label>
                    <select class="form-select @error('ad_type') is-invalid @enderror" name="ad_type" required>
                        <option value="">Select Ad Type</option>
                        @foreach ($adTypes as $item)
                            <option value="{{ $item->id }}"
                                {{ old('ad_type', $ad->ad_type ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->ad_type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('ad_type')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Start Date <span class="req">*</span></label>
                    <input type="text" class="form-control flatpickr" name="start_date"
                        value="{{ old('start_date', $ad->start_date->format('Y-m-d')) }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">End Date <span class="req">*</span></label>
                    <input type="text" class="form-control flatpickr" name="end_date"
                        value="{{ old('end_date', $ad->end_date->format('Y-m-d')) }}" required />
                </div>

                <div class="col-md-4">
                    <label class="form-label">External Link / URL (optional)</label>
                    <input type="url" class="form-control" name="external_url"
                        value="{{ old('external_url', $ad->external_url) }}" />
                </div>

                <div class="col-md-12">
                    <label class="form-label">Ad Description / Text</label>
                    <textarea class="form-control" name="ad_text" rows="3">{{ old('ad_text', $ad->ad_text) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Current Ad Image</label>
                    <div class="mb-2">
                        <img src="{{ asset('uploads/ads/' . $ad->ad_image) }}" alt="Current Ad"
                            style="height: 100px; border-radius: 5px; border: 1px solid #ddd;">
                    </div>
                    <label class="form-label">Change Image (optional)</label>
                    <input type="file" class="form-control" name="ad_image" accept="image/*" />
                </div>

                <div class="col-md-6">
                    @if ($ad->ad_video)
                        <label class="form-label">Current Video</label>
                        <div class="mb-2">
                            <span class="badge bg-secondary">Video Uploaded</span>
                        </div>
                    @endif
                    <label class="form-label">Change Video (optional)</label>
                    <input type="file" class="form-control" name="ad_video" accept="video/*" />
                </div>
            </div>

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
