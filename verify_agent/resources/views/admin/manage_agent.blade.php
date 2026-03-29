<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Agents</title>
    @include('admin.includes.header_links')
</head>

<body>

    @include('admin.includes.sidebar')
    @include('admin.includes.header')

    <div class="content">

        <div class="page-head">
            <h1 class="page-title">Manage Agents</h1>

            <div class="action-group">
                <a href="{{ route('exportAgents', request()->query()) }}" class="btn-action btn-manage">
                    Export CSV
                </a>
                <a href="{{ route('createAgent') }}" class="btn-action btn-manage">+ Add New Agent</a>
            </div>
        </div>

        <div class="property-form">

         <div class="filter-section mb-4">
    <form action="{{ route('manageAgent') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Search Agent</label>
            <input type="text" name="search" class="form-control" placeholder="Name, email or phone"
                value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
                <option value="">All</option>
                <option value="0" {{ request('priority') === '0' ? 'selected' : '' }}>High</option>
                <option value="1" {{ request('priority') === '1' ? 'selected' : '' }}>Medium</option>
                <option value="2" {{ request('priority') === '2' ? 'selected' : '' }}>Low</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Constituency</label>
            <input type="text" name="constituency" class="form-control" placeholder="Serilingampally"
                value="{{ request('constituency') }}">
        </div>

        <div class="col-md-2 d-flex gap-2 align-items-end">
            <button type="submit" class="btn-save">Submit</button>
            <a href="{{ route('manageAgent') }}" class="btn btn-secondary">Reset</a>
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
                            <th>Name</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>RERA</th>
                            <th>City</th>
                            <th>Constituency</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Joined On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($agents as $agent)
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>{{ ($agents->currentPage() - 1) * $agents->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <strong>{{ $agent->agent_name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $agent->username }}</td>
                                <td>{{ $agent->mobile_number }}</td>
                                <td>{{ $agent->rera_no }}</td>
                                <td>{{ $agent->location }}</td>
                                <td>{{ $agent->constituency }}</td>
                                <td>
                                    @if ($agent->priority == 0)
                                        High
                                    @elseif($agent->priority == 1)
                                        Medium
                                    @else
                                        Low
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="status-badge {{ $agent->status == 1 ? 'status-active' : 'status-pending' }}">
                                        {{ $agent->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $agent->created_at->format('d M Y') }}</td>
                                <td class="action-cell">
                                    <button class="action-btn btn-view">View</button>

                                    <a href="{{ route('editAgent', $agent->id) }}"
                                        class="action-btn btn-edit"
                                        style="text-decoration: none; display: inline-block;">Edit</a>

                                    <form action="{{ route('deleteAgent', $agent->id) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this agent?')">
                                        @csrf
                                        <button type="submit" class="action-btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No agents found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-container mt-3">
                <div class="pagination-info">
                    Showing {{ $agents->firstItem() ?? 0 }}–{{ $agents->lastItem() ?? 0 }} of {{ $agents->total() }}
                    agents
                </div>
                <nav>
                    {{ $agents->appends(request()->query())->links('pagination::bootstrap-4') }}
                </nav>
            </div>

        </div>
    </div>

    @include('admin.includes.footer_links')

</body>

</html>
