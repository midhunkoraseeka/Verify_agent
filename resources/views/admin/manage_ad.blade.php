<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Advertisements</title>
    @include('admin.includes.header_links')
</head>

<body>

    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">Manage Advertisements</h1>

            <div class="action-group">
                <a href="{{ route('exportAds', request()->query()) }}" class="btn-action btn-manage">
                    Export CSV
                </a>
                <a href="{{ route('createAd') }}" class="btn-action btn-manage">+ Add New Ad</a>
            </div>
        </div>

        <div class="property-form">

            <div class="filter-section mb-4">
                <form action="{{ route('manageAds') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Search Ad</label>
                        <input type="text" name="search" class="form-control" placeholder="Title or description..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Ad Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach ($adTypes as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('type') == $item->id ? 'selected' : '' }}>
                                    {{ $item->ad_type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Expired</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Paused</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2 align-items-end">
                        <button type="submit" class="btn-save">Submit</button>
                        <a href="{{ route('manageAds') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>#</th>
                            <th>Ad Title</th>
                            <th>Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Clicks</th>
                            <th>Added On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ads as $ad)
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>{{ ($ads->currentPage() - 1) * $ads->perPage() + $loop->iteration }}</td>
                                <td>
                                    <strong>{{ Str::limit($ad->ad_title, 35) }}</strong>
                                </td>

                                {{-- FIX: Display the type name via the eager-loaded relationship --}}
                                {{-- Falls back to 'N/A' if the type was deleted --}}
                                <td>{{ $ad->adType->ad_type_name ?? 'N/A' }}</td>

                                <td>{{ $ad->start_date ? date('d M Y', strtotime($ad->start_date)) : 'N/A' }}</td>
                                <td>{{ $ad->end_date ? date('d M Y', strtotime($ad->end_date)) : 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = match ((int) $ad->status) {
                                            1 => 'status-active',
                                            2 => 'status-sold',
                                            0 => 'status-pending',
                                            3 => 'status-pending',
                                            default => 'status-pending',
                                        };
                                        $statusText = match ((int) $ad->status) {
                                            1 => 'Active',
                                            2 => 'Expired',
                                            0 => 'Pending',
                                            3 => 'Paused',
                                            default => 'Unknown',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>{{ number_format($ad->views ?? 0) }}</td>
                                <td>{{ number_format($ad->clicks ?? 0) }}</td>
                                <td>{{ $ad->created_at->format('d M Y') }}</td>
                                <td class="action-cell">
                                    <button class="action-btn btn-view">View</button>

                                    <a href="{{ route('editAd', $ad->id) }}" class="action-btn btn-edit"
                                        style="text-decoration: none; display: inline-block;">Edit</a>

                                    <form action="{{ route('deleteAd', $ad->id) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this ad?')">
                                        @csrf
                                        <button type="submit" class="action-btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No advertisements found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-container mt-3">
                <div class="pagination-info">
                    Showing {{ $ads->firstItem() ?? 0 }}–{{ $ads->lastItem() ?? 0 }} of {{ $ads->total() }} ads
                </div>
                <nav>
                    {{ $ads->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    </div>

    @include('admin.includes.footer_links')

</body>

</html>