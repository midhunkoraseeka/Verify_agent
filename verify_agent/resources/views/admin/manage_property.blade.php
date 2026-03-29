<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Properties</title>

    @include('admin.includes.header_links')

    <style>
        .table-property-type {
            font-weight: 600;
        }

        .property-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-villa {
            background: #eef2ff;
            color: #3730a3;
        }

        .badge-plot {
            background: #ecfeff;
            color: #155e75;
        }

        .badge-land {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-flat {
            background: #ecfdf5;
            color: #065f46;
        }

        .badge-individual {
            background: #fff1f2;
            color: #9f1239;
        }

        .badge-lease {
            background: #f0f9ff;
            color: #075985;
        }

        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            color: #0369a1;
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .filter-tag a {
            color: #0369a1;
            text-decoration: none;
            font-weight: 700;
            line-height: 1;
        }

        .filter-tag a:hover {
            color: #dc2626;
        }

        .btn-clear-all {
            font-size: 12px;
            color: #6b7280;
            text-decoration: underline;
            background: none;
            border: none;
            cursor: pointer;
            padding: 3px 6px;
        }

        .btn-clear-all:hover {
            color: #dc2626;
        }
    </style>
</head>

<body>

    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">Manage Property Listings</h1>
            <div class="action-group">
                <a href="{{ route('exportProperties', request()->query()) }}"
                    class="btn-action btn-manage text-decoration-none">Export CSV</a>
                <a href="{{ route('createProperty') }}" class="btn-action btn-manage">+ Add Property</a>
            </div>
        </div>

        <div class="property-form">

            {{-- ── Filter Form ── --}}
            <div class="filter-section mb-3">
                <form action="{{ route('manageProperty') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input name="search" class="form-control" placeholder="Location / Price"
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Property Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Villa
                                </option>
                                <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>
                                    Individual House</option>
                                <option value="3" {{ request('type') == '3' ? 'selected' : '' }}>Flat
                                </option>
                                <option value="4" {{ request('type') == '4' ? 'selected' : '' }}>
                                    Residential Plot</option>
                                <option value="5" {{ request('type') == '5' ? 'selected' : '' }}>Land
                                </option>
                                <option value="6" {{ request('type') == '6' ? 'selected' : '' }}>Lease
                                    Property</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Approved By</label>
                            <select name="approved_by" class="form-select">
                                <option value="">All</option>
                                <option value="GP" {{ request('approved_by') == 'GP' ? 'selected' : '' }}>GP
                                </option>
                                <option value="DTCP" {{ request('approved_by') == 'DTCP' ? 'selected' : '' }}>DTCP
                                </option>
                                <option value="HMDA" {{ request('approved_by') == 'HMDA' ? 'selected' : '' }}>HMDA
                                </option>
                                <option value="GHMC" {{ request('approved_by') == 'GHMC' ? 'selected' : '' }}>GHMC
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Price Type</label>
                            <select name="price_type" class="form-select">
                                <option value="">All</option>
                                <option value="Fixed" {{ request('price_type') == 'Fixed' ? 'selected' : '' }}>
                                    Fixed</option>
                                <option value="Negotiable"
                                    {{ request('price_type') == 'Negotiable' ? 'selected' : '' }}>Negotiable</option>
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn-save w-100">Apply Filters</button>
                            @if (request()->hasAny(['search', 'type', 'approved_by', 'price_type']))
                                <a href="{{ route('manageProperty') }}" class="btn-action btn-manage"
                                    style="white-space:nowrap">Clear</a>
                            @endif
                        </div>

                    </div>
                </form>
            </div>

            {{-- ── Active Filter Tags ── --}}
            @php
                $typeLabels = [
                    '1' => 'Villa',
                    '2' => 'Individual House',
                    '3' => 'Flat',
                    '4' => 'Residential Plot',
                    '5' => 'Land',
                    '6' => 'Lease Property',
                ];
                $activeFilters = array_filter([
                    'search' => request('search'),
                    'type' => request('type'),
                    'approved_by' => request('approved_by'),
                    'price_type' => request('price_type'),
                ]);
            @endphp

            @if (count($activeFilters))
                <div class="active-filters mb-3">
                    @if (request('search'))
                        <span class="filter-tag">
                            Search: "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" title="Remove">×</a>
                        </span>
                    @endif
                    @if (request('type'))
                        <span class="filter-tag">
                            Type: {{ $typeLabels[request('type')] ?? request('type') }}
                            <a href="{{ request()->fullUrlWithoutQuery(['type']) }}" title="Remove">×</a>
                        </span>
                    @endif
                    @if (request('approved_by'))
                        <span class="filter-tag">
                            Approved By: {{ request('approved_by') }}
                            <a href="{{ request()->fullUrlWithoutQuery(['approved_by']) }}" title="Remove">×</a>
                        </span>
                    @endif
                    @if (request('price_type'))
                        <span class="filter-tag">
                            Price: {{ request('price_type') }}
                            <a href="{{ request()->fullUrlWithoutQuery(['price_type']) }}" title="Remove">×</a>
                        </span>
                    @endif
                </div>
            @endif

            {{-- ── Alerts ── --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->has('error'))
                <div class="alert alert-danger">{{ $errors->first('error') }}</div>
            @endif

            {{-- ── Table ── --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>Property Type</th>
                            <th>Details</th>
                            <th>Approval / Zone</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Added On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($properties as $property)
                            <tr>
                                <td><input type="checkbox" class="row-check"></td>
                                <td>{{ ($properties->currentPage() - 1) * $properties->perPage() + $loop->iteration }}
                                </td>
                                @php
                                    $typeLabels = [
                                        1 => 'Villa',
                                        2 => 'Individual House',
                                        3 => 'Flat',
                                        4 => 'Residential Plot',
                                        5 => 'Land',
                                        6 => 'Lease Property',
                                    ];
                                @endphp

                                <td>
                                    <span class="property-badge badge-{{ $property->property_type }}">
                                        {{ $typeLabels[$property->property_type] ?? 'Unknown' }}
                                    </span>
                                </td>

                                <td>

                                    {{-- VILLA / INDIVIDUAL HOUSE --}}
                                    @if (in_array($property->property_type, [1, 2]))
                                        {{ implode(
                                            ' · ',
                                            array_filter([
                                                $property->bhk->bhk_type_name ?? null,
                                                $property->community_type,
                                                $property->floor->floor_name ?? null,
                                                $property->size ? $property->size . ' Sq Ft' : null,
                                            ]),
                                        ) ?:
                                            'N/A' }}

                                        <br>

                                        {{ implode(
                                            ' · ',
                                            array_filter([$property->facingDirection->facing_name ?? null, $property->parking->parking_name ?? null]),
                                        ) ?:
                                            'N/A' }}

                                        {{-- FLAT --}}
                                    @elseif($property->property_type == 3)
                                        {{ implode(
                                            ' · ',
                                            array_filter([
                                                $property->bhk->bhk_type_name ?? null,
                                                $property->community_type,
                                                $property->size ? $property->size . ' Sq Ft' : null,
                                            ]),
                                        ) ?:
                                            'N/A' }}

                                        <br>

                                        {{ implode(
                                            ' · ',
                                            array_filter([$property->floor->floor_name ?? null, $property->parking->parking_name ?? 'N/A']),
                                        ) ?:
                                            'N/A' }}

                                        {{-- RESIDENTIAL PLOT --}}
                                    @elseif($property->property_type == 4)
                                        {{ implode(
                                            ' · ',
                                            array_filter([
                                                $property->size ? $property->size . ' Sq Yds' : null,
                                                $property->facingDirection->facing_name ?? null,
                                            ]),
                                        ) ?:
                                            'N/A' }}

                                        <br>

                                        {{ $property->roadSize->road_size_name ? 'Road: ' . $property->roadSize->road_size_name : 'N/A' }}

                                        {{-- LAND / LEASE --}}
                                    @elseif(in_array($property->property_type, [5, 6]))
                                        {{ implode(' · ', array_filter([$property->land_type, $property->owner_type])) ?: 'N/A' }}

                                        <br>

                                        {{ $property->amenities ? 'Zone: ' . $property->amenities : 'N/A' }}
                                    @else
                                        N/A
                                    @endif

                                </td>


                                <td>
                                    @if ($property->approved_by)
                                        @php
                                            $noSuffix = ['NALA Conversion', 'HMDA'];
                                            $approval = in_array($property->approved_by, $noSuffix)
                                                ? $property->approved_by
                                                : $property->approved_by . ' Approved';
                                        @endphp
                                        {{ $approval }}
                                        @if ($property->rera_status)
                                            · RERA: {{ $property->rera_status }}
                                        @endif
                                    @elseif($property->land_type)
                                        {{ $property->land_type }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td>{{ $property->location ?? 'N/A' }}</td>

                                <td>
                                    @if ($property->price)
                                        @php
                                            $p = $property->price;
                                            if ($p >= 10000000) {
                                                $fmt =
                                                    '₹ ' .
                                                    rtrim(rtrim(number_format($p / 10000000, 2), '0'), '.') .
                                                    ' Cr';
                                            } elseif ($p >= 100000) {
                                                $fmt =
                                                    '₹ ' . rtrim(rtrim(number_format($p / 100000, 2), '0'), '.') . ' L';
                                            } else {
                                                $fmt = '₹ ' . number_format($p);
                                            }
                                            $unit = match ($property->property_type) {
                                                'plot' => ' / Sq Yd',
                                                'land', 'lease' => ' / Acre',
                                                default => '',
                                            };
                                        @endphp
                                        {{ $fmt }}{{ $unit }}
                                        @if ($property->price_type)
                                            ({{ $property->price_type }})
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td>{{ $property->created_at->format('d M Y') }}</td>

                                <td class="action-cell">
                                    <a href="#" class="action-btn btn-view text-decoration-none">View</a>

                                    {{-- Dynamic Edit Link --}}
                                    <a href="{{ route('editProperty', $property->id) }}"
                                        class="action-btn btn-edit text-decoration-none">Edit</a>

                                    <a href="{{ route('deleteProperty', $property->id) }}"
                                        class="action-btn btn-delete text-decoration-none"
                                        onclick="return confirm('Move to trash?')">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    No properties found
                                    @if (count($activeFilters))
                                        for the selected filters
                                    @endif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── Pagination ── --}}
            <div class="pagination-container mt-3">
                <div class="pagination-info">
                    @if ($properties->total() > 0)
                        Showing {{ $properties->firstItem() }}–{{ $properties->lastItem() }} of
                        {{ $properties->total() }} {{ Str::plural('property', $properties->total()) }}
                    @else
                        No properties found
                    @endif
                </div>
                <nav>
                    {{ $properties->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>

        </div>
    </div>

    @include('admin.includes.footer_links')

    <script>
        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
        });
    </script>

</body>

</html>
