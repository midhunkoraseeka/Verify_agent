<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Property Listing</title>
    @include('admin.includes.header_links')
    <style>
        .section-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 20px; margin-bottom: 20px; }
        .section-title { font-weight: 600; margin-bottom: 15px; font-size: 16px; }
        .hidden { display: none }
        .property-form .form-control, .property-form .form-select { height: 44px; }
        .property-form textarea { height: 44px; resize: none; }
        .is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback { display: block; color: #dc3545; font-size: 12px; margin-top: 4px; }
    </style>
</head>

<body>
    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Add Property Listing</h1>
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

        {{-- Added ID: propertyForm --}}
        <form id="propertyForm" class="property-form" action="{{ route('storeProperty') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="section-box">
                <div class="section-title">Property Type</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select @error('property_type') is-invalid @enderror" id="propertyType" name="property_type">
                            <option value="">Select Property Type</option>
                            <option value="1" {{ old('property_type') == '1' ? 'selected' : '' }}>Villa</option>
                            <option value="2" {{ old('property_type') == '2' ? 'selected' : '' }}>Individual House</option>
                            <option value="3" {{ old('property_type') == '3' ? 'selected' : '' }}>Flat</option>
                            <option value="4" {{ old('property_type') == '4' ? 'selected' : '' }}>Residential Plot</option>
                            <option value="5" {{ old('property_type') == '5' ? 'selected' : '' }}>Land</option>
                            <option value="6" {{ old('property_type') == '6' ? 'selected' : '' }}>Lease Property</option>
                        </select>
                        @error('property_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="section-box hidden" id="villaSection">
                <div class="section-title">Villa Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="community_type">
                            <option value="">Community Type</option>
                            <option value="Gated Community" {{ old('community_type') == 'Gated Community' ? 'selected' : '' }}>Gated Community</option>
                            <option value="Regular" {{ old('community_type') == 'Regular' ? 'selected' : '' }}>Regular</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="facing">
                            <option value="">Facing</option>
                            @foreach ($facing as $item) <option value="{{ $item->id }}" {{ old('facing') == $item->id ? 'selected' : '' }}>{{ $item->facing_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="bhk_type">
                            <option value="">BHK Type</option>
                            @foreach ($bhks as $item) <option value="{{ $item->id }}" {{ old('bhk_type') == $item->id ? 'selected' : '' }}>{{ $item->bhk_type_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="size" placeholder="Size (Sq Ft / Sq Yard)" value="{{ old('size') }}"></div>
                    <div class="col-md-4">
                        <select class="form-select" name="floors">
                            <option value="">Select Floors</option>
                            @foreach ($floors as $item) <option value="{{ $item->id }}" {{ old('floors') == $item->id ? 'selected' : '' }}>{{ $item->floor_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="road_size">
                            <option value="">Road Size</option>
                            @foreach ($roads as $item) <option value="{{ $item->id }}" {{ old('road_size') == $item->id ? 'selected' : '' }}>{{ $item->road_size_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="car_parking">
                            <option value="">Car Parking</option>
                            @foreach ($parkings as $item) <option value="{{ $item->id }}" {{ old('car_parking') == $item->id ? 'selected' : '' }}>{{ $item->parking_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="amenities" placeholder="Amenities" value="{{ old('amenities') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="price" placeholder="Price" value="{{ old('price') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="location" placeholder="Location" value="{{ old('location') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="location_highlights" placeholder="Location Highlights" value="{{ old('location_highlights') }}"></div>
                    <div class="col-md-4"><input type="file" name="images[]" class="form-control" multiple accept="image/*"></div>
                </div>
            </div>

            <div class="section-box hidden" id="individualSection">
                <div class="section-title">Individual House Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="facing">
                            <option value="">Facing</option>
                            @foreach ($facing as $item) <option value="{{ $item->id }}">{{ $item->facing_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="size" placeholder="Size" value="{{ old('size') }}"></div>
                    <div class="col-md-4">
                        <select class="form-select" name="bhk_type">
                            <option value="">BHK Type</option>
                            @foreach ($bhks as $item) <option value="{{ $item->id }}">{{ $item->bhk_type_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="car_parking">
                            <option value="">Car Parking</option>
                            @foreach ($parkings as $item) <option value="{{ $item->id }}">{{ $item->parking_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="floors">
                            <option value="">Floors</option>
                            @foreach ($floors as $item) <option value="{{ $item->id }}">{{ $item->floor_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="road_size">
                            <option value="">Road Size</option>
                            @foreach ($roads as $item) <option value="{{ $item->id }}">{{ $item->road_size_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="approved_by">
                            <option value="">Approved By</option>
                            @foreach ($approvals as $item) <option value="{{ $item->id }}">{{ $item->approval_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="location" placeholder="Location" value="{{ old('location') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="price" placeholder="Price" value="{{ old('price') }}"></div>
                    <div class="col-md-4"><input type="file" name="images[]" class="form-control" multiple accept="image/*"></div>
                    <div class="col-md-4"><input class="form-control" name="location_highlights" placeholder="Location Highlights" value="{{ old('location_highlights') }}"></div>
                </div>
            </div>

            <div class="section-box hidden" id="flatSection">
                <div class="section-title">Flat Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="community_type">
                            <option value="">Community Type</option>
                            <option value="Gated Community">Gated Community</option>
                            <option value="Regular">Regular</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="facing">
                            <option value="">Facing</option>
                            @foreach ($facing as $item) <option value="{{ $item->id }}">{{ $item->facing_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="bhk_type">
                            <option value="">BHK Type</option>
                            @foreach ($bhks as $item) <option value="{{ $item->id }}">{{ $item->bhk_type_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="size" placeholder="Size" value="{{ old('size') }}"></div>
                    <div class="col-md-4">
                        <select class="form-select" name="approved_by">
                            <option value="">Approved By</option>
                            @foreach ($approvals as $item) <option value="{{ $item->id }}">{{ $item->approval_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="location" placeholder="Location" value="{{ old('location') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="price" placeholder="Price" value="{{ old('price') }}"></div>
                    <div class="col-md-4"><input type="file" name="images[]" class="form-control" multiple accept="image/*"></div>
                    <div class="col-md-4"><input class="form-control" name="location_highlights" placeholder="Location Highlights" value="{{ old('location_highlights') }}"></div>
                </div>
            </div>

            <div class="section-box hidden" id="plotSection">
                <div class="section-title">Residential Plot Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" id="approvedByPlot" name="approved_by">
                            <option value="">Approved By</option>
                            <option value="GP">GP</option>
                            <option value="DTCP">DTCP</option>
                            <option value="HMDA">HMDA</option>
                            <option value="NALA Conversion">NALA Conversion</option>
                        </select>
                    </div>
                    <div class="col-md-4 hidden" id="reraField">
                        <select class="form-select" name="rera_status">
                            <option value="">RERA Approved?</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="facing">
                            <option value="">Facing</option>
                            @foreach ($facing as $item) <option value="{{ $item->id }}">{{ $item->facing_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="road_size">
                            <option value="">Road Size</option>
                            @foreach ($roads as $item) <option value="{{ $item->id }}">{{ $item->road_size_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="size" placeholder="Square Yard" value="{{ old('size') }}"></div>
                    <div class="col-md-4">
                        <select class="form-select" name="price_type">
                            <option value="">Price Type</option>
                            <option value="Fixed">Fixed</option>
                            <option value="Negotiable">Negotiable</option>
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="location" placeholder="Location Pin" value="{{ old('location') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="price" placeholder="Price" value="{{ old('price') }}"></div>
                    <div class="col-md-4"><input type="file" name="images[]" class="form-control" multiple accept="image/*"></div>
                    <div class="col-md-4"><input class="form-control" name="location_highlights" placeholder="Nearby Highlights" value="{{ old('location_highlights') }}"></div>
                </div>
            </div>

            <div class="section-box hidden" id="landSection">
                <div class="section-title">Land / Lease Details</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="land_type">
                            <option value="">Land Type</option>
                            @foreach ($land_types as $item) <option value="{{ $item->id }}">{{ $item->land_type_name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="owner_type">
                            <option value="">Owner Type</option>
                            <option value="Direct">Direct</option>
                            <option value="Agreement Holder">Agreement Holder</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="price_type">
                            <option value="">Price Type</option>
                            <option value="Fixed">Fixed</option>
                            <option value="Negotiable">Negotiable</option>
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="amenities" placeholder="Zone" value="{{ old('amenities') }}"></div>
                    <div class="col-md-4">
                        <select class="form-select" name="conversion_type">
                            <option value="">Conversion Type</option>
                            <option value="Passbook">Passbook</option>
                            <option value="NALA Conversion">NALA Conversion</option>
                        </select>
                    </div>
                    <div class="col-md-4"><input class="form-control" name="location" placeholder="Location" value="{{ old('location') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="price" placeholder="Price" value="{{ old('price') }}"></div>
                    <div class="col-md-4"><input class="form-control" name="location_highlights" placeholder="Location Highlights" value="{{ old('location_highlights') }}"></div>
                    <div class="col-md-4"><input type="file" name="images[]" class="form-control" multiple accept="image/*"></div>
                    <div class="col-md-4"><label class="form-label">Video (Optional)</label><input type="file" name="property_video" class="form-control" accept="video/*"></div>
                </div>
            </div>

            <div class="form-actions mb-5">
                <button type="submit" class="btn-save">Save Property</button>
            </div>
        </form>
    </div>

    @include('admin.includes.footer_links')

    <script>
        const typeSelect = document.getElementById('propertyType');
        const villa = document.getElementById('villaSection');
        const individual = document.getElementById('individualSection');
        const flat = document.getElementById('flatSection');
        const plot = document.getElementById('plotSection');
        const land = document.getElementById('landSection');
        const allSections = [villa, individual, flat, plot, land];

        function handleSectionVisibility() {
            const val = typeSelect.value;
            
            // Hide all and DISABLE all inputs within them
            allSections.forEach(s => {
                s.classList.add('hidden');
                s.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
            });

            // Show and ENABLE selected section
            let active = null;
            if (val === '1') active = villa;
            else if (val === '2') active = individual;
            else if (val === '3') active = flat;
            else if (val === '4') active = plot;
            else if (val === '5' || val === '6') active = land;

            if (active) {
                active.classList.remove('hidden');
                active.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
            }
        }

        typeSelect.addEventListener('change', handleSectionVisibility);

        // Run on load to handle validation redirects (old() values)
        window.addEventListener('DOMContentLoaded', handleSectionVisibility);

        // Also handle RERA toggle for Plots
        document.getElementById('approvedByPlot').addEventListener('change', function() {
            const rera = document.getElementById('reraField');
            if (['DTCP', 'HMDA'].includes(this.value)) {
                rera.classList.remove('hidden');
                rera.querySelector('select').disabled = false;
            } else {
                rera.classList.add('hidden');
                rera.querySelector('select').disabled = true;
            }
        });
    </script>
</body>
</html>