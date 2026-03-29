<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Property Listing</title>
    @include('admin.includes.header_links')
    <style>
        .section-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; margin-bottom: 20px; }
        .section-title { font-weight: 600; margin-bottom: 15px; font-size: 16px; }
        .hidden { display: none }
        .property-form .form-control, .property-form .form-select { height: 44px; }
        .property-form textarea { height: 44px; resize: none; }
        .is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback { display: none; color: #dc3545; font-size: 12px; margin-top: 4px; }
        .is-invalid~.invalid-feedback { display: block; }
        .preview-img { width: 100px; height: 75px; object-fit: cover; border-radius: 4px; margin: 5px; border: 1px solid #ddd; }
        .current-media-box { background: #f9fafb; border-radius: 6px; padding: 15px; }
    </style>
</head>
<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Edit Property: #{{ $property->id }}</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mx-1 mb-3">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="property-form" id="propertyForm" action="{{ route('updateProperty', $property->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- 1. PROPERTY TYPE --}}
            <div class="section-box">
                <div class="section-title">Property Type</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select @error('property_type') is-invalid @enderror" id="propertyType" name="property_type">
                            <option value="">Select Property Type</option>
                            @foreach(['1'=>'Villa', '2'=>'Individual House', '3'=>'Flat', '4'=>'Residential Plot', '5'=>'Land', '6'=>'Lease Property'] as $val => $label)
                                <option value="{{ $val }}" {{ old('property_type', $property->property_type) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a property type.</div>
                    </div>
                </div>
            </div>

            {{-- 2. VILLA SECTION --}}
            <div class="section-box {{ old('property_type', $property->property_type) == '1' ? '' : 'hidden' }}" id="villaSection">
                <div class="section-title">Villa Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="community_type">
                            <option value="">Community Type</option>
                            <option value="Gated Community" {{ old('community_type', $property->community_type) == 'Gated Community' ? 'selected' : '' }}>Gated Community</option>
                            <option value="Regular" {{ old('community_type', $property->community_type) == 'Regular' ? 'selected' : '' }}>Regular</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="facing">
                            <option value="">Select Facing</option>
                            @foreach ($facing as $item)
                                <option value="{{ $item->id }}" {{ old('facing', $property->facing) == $item->id ? 'selected' : '' }}>{{ $item->facing_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="bhk_type">
                            <option value="">Select BHK Type</option>
                            @foreach ($bhks as $item)
                                <option value="{{ $item->id }}" {{ old('bhk_type', $property->bhk_type) == $item->id ? 'selected' : '' }}>{{ $item->bhk_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control" name="size" placeholder="Size (Sq Ft)" value="{{ old('size', $property->size) }}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="floors">
                            <option value="">Select Floors</option>
                            @foreach ($floors as $item)
                                <option value="{{ $item->id }}" {{ old('floors', $property->floors) == $item->id ? 'selected' : '' }}>{{ $item->floor_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="road_size">
                            <option value="">Select Road Size</option>
                            @foreach ($roads as $item)
                                <option value="{{ $item->id }}" {{ old('road_size', $property->road_size) == $item->id ? 'selected' : '' }}>{{ $item->road_size_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="car_parking">
                            <option value="">Select Parking</option>
                            @foreach ($parkings as $item)
                                <option value="{{ $item->id }}" {{ old('car_parking', $property->car_parking) == $item->id ? 'selected' : '' }}>{{ $item->parking_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control villa-required" name="price" placeholder="Price *" value="{{ old('price', $property->price) }}">
                    </div>
                    <div class="col-md-4">
                        <input class="form-control villa-required" name="location" placeholder="Location *" value="{{ old('location', $property->location) }}">
                    </div>
                </div>
            </div>

            {{-- 3. INDIVIDUAL HOUSE SECTION --}}
            <div class="section-box {{ old('property_type', $property->property_type) == '2' ? '' : 'hidden' }}" id="individualSection">
                <div class="section-title">Individual House Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="facing">
                            <option value="">Select Facing</option>
                            @foreach ($facing as $item)
                                <option value="{{ $item->id }}" {{ old('facing', $property->facing) == $item->id ? 'selected' : '' }}>{{ $item->facing_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="bhk_type">
                            <option value="">Select BHK Type</option>
                            @foreach ($bhks as $item)
                                <option value="{{ $item->id }}" {{ old('bhk_type', $property->bhk_type) == $item->id ? 'selected' : '' }}>{{ $item->bhk_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control individual-required" name="price" placeholder="Price *" value="{{ old('price', $property->price) }}">
                    </div>
                    <div class="col-md-4">
                        <input class="form-control individual-required" name="location" placeholder="Location *" value="{{ old('location', $property->location) }}">
                    </div>
                </div>
            </div>

            {{-- 4. FLAT SECTION --}}
            <div class="section-box {{ old('property_type', $property->property_type) == '3' ? '' : 'hidden' }}" id="flatSection">
                <div class="section-title">Flat Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="community_type">
                            <option value="">Community Type</option>
                            <option value="Gated Community" {{ old('community_type', $property->community_type) == 'Gated Community' ? 'selected' : '' }}>Gated Community</option>
                            <option value="Regular" {{ old('community_type', $property->community_type) == 'Regular' ? 'selected' : '' }}>Regular</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="bhk_type">
                            <option value="">Select BHK</option>
                            @foreach ($bhks as $item)
                                <option value="{{ $item->id }}" {{ old('bhk_type', $property->bhk_type) == $item->id ? 'selected' : '' }}>{{ $item->bhk_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control flat-required" name="price" placeholder="Price *" value="{{ old('price', $property->price) }}">
                    </div>
                    <div class="col-md-4">
                        <input class="form-control flat-required" name="location" placeholder="Location *" value="{{ old('location', $property->location) }}">
                    </div>
                </div>
            </div>

            {{-- 5. PLOT SECTION --}}
            <div class="section-box {{ old('property_type', $property->property_type) == '4' ? '' : 'hidden' }}" id="plotSection">
                <div class="section-title">Residential Plot Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select plot-required" id="approvedByPlot" name="approved_by">
                            <option value="">Approved By *</option>
                            @foreach ($approvals as $item)
                                <option value="{{ $item->id }}" {{ old('approved_by', $property->approved_by) == $item->id ? 'selected' : '' }}>{{ $item->approval_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control plot-required" name="size" placeholder="Square Yard *" value="{{ old('size', $property->size) }}">
                    </div>
                    <div class="col-md-4">
                        <input class="form-control plot-required" name="price" placeholder="Price / Sq Yard *" value="{{ old('price', $property->price) }}">
                    </div>
                    <div class="col-md-4">
                        <input class="form-control plot-required" name="location" placeholder="Location *" value="{{ old('location', $property->location) }}">
                    </div>
                </div>
            </div>

            {{-- 6. LAND / LEASE SECTION --}}
            <div class="section-box {{ in_array(old('property_type', $property->property_type), ['5', '6']) ? '' : 'hidden' }}" id="landSection">
                <div class="section-title">Land / Lease Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select land-required" name="land_type">
                            <option value="">Land Type *</option>
                            @foreach ($land_types as $item)
                                <option value="{{ $item->id }}" {{ old('land_type', $property->land_type) == $item->id ? 'selected' : '' }}>{{ $item->land_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input class="form-control land-required" name="price" placeholder="Price *" value="{{ old('price', $property->price) }}">
                    </div>
                    <div class="col-md-4">
                        <input class="form-control land-required" name="location" placeholder="Location *" value="{{ old('location', $property->location) }}">
                    </div>
                </div>
            </div>

            {{-- MEDIA SECTION --}}
            <div class="section-box">
                <div class="section-title">Media & Documents</div>
                
                {{-- Existing Media Preview --}}
                <div class="current-media-box mb-3">
                    <p class="small fw-bold text-muted mb-2">Current Images:</p>
                    <div class="d-flex flex-wrap">
                        @if($property->images && count($property->images) > 0)
                            @foreach($property->images as $img)
                                <img src="{{ asset('uploads/properties/'.$img) }}" class="preview-img" alt="Property">
                            @endforeach
                        @else
                            <span class="text-muted small">No images uploaded.</span>
                        @endif
                    </div>
                    
                    @if($property->video)
                        <div class="mt-3">
                            <p class="small fw-bold text-muted mb-1">Current Video:</p>
                            <video width="200" controls>
                                <source src="{{ asset('uploads/properties/'.$property->video) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Add More Images</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Replace Video</label>
                        <input type="file" name="property_video" class="form-control" accept="video/*">
                    </div>
                </div>
            </div>

            <div class="form-actions mb-5">
                <button type="submit" class="btn-save px-5">Update Property Listing</button>
                <a href="{{ route('manageProperty') }}" class="btn btn-outline-secondary py-2 px-4 ms-2" style="height:44px; line-height:28px;">Cancel</a>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links')

    <script>
        const type = document.getElementById('propertyType');
        const villa = document.getElementById('villaSection');
        const individual = document.getElementById('individualSection');
        const flat = document.getElementById('flatSection');
        const plot = document.getElementById('plotSection');
        const land = document.getElementById('landSection');
        const allSections = [villa, individual, flat, plot, land];

        type.addEventListener('change', () => {
            allSections.forEach(s => s.classList.add('hidden'));
            const val = type.value;
            if (val === 'villa') villa.classList.remove('hidden');
            if (val === 'individual') individual.classList.remove('hidden');
            if (val === 'flat') flat.classList.remove('hidden');
            if (val === 'plot') plot.classList.remove('hidden');
            if (val === 'land' || val === 'lease') land.classList.remove('hidden');
        });

        // Validation & Helper Logic (Same as your Add page)
        function markInvalid(el, msg) {
            el.classList.add('is-invalid');
            const fb = el.nextElementSibling;
            if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = msg;
        }

        function markValid(el) { el.classList.remove('is-invalid'); }

        document.getElementById('propertyForm').addEventListener('submit', function(e) {
            let valid = true;
            if (!type.value) { type.classList.add('is-invalid'); valid = false; }

            const requiredClassMap = {
                'villa': 'villa-required', 'individual': 'individual-required',
                'flat': 'flat-required', 'plot': 'plot-required',
                'land': 'land-required', 'lease': 'land-required',
            };

            const activeClass = requiredClassMap[type.value];
            if (activeClass) {
                document.querySelectorAll('.' + activeClass).forEach(function(el) {
                    if (!el.value || el.value.trim() === '') {
                        markInvalid(el, 'This field is required.');
                        valid = false;
                    }
                });
            }

            if (!valid) {
                e.preventDefault();
                const firstError = document.querySelector('.is-invalid');
                if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // Disable hidden inputs so they don't send empty data
                allSections.forEach(section => {
                    if (section.classList.contains('hidden')) {
                        section.querySelectorAll('input, select').forEach(el => el.disabled = true);
                    }
                });
            }
        });
    </script>
</body>
</html>