<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Loan Types</title>
    @include('admin.includes.header_links')
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">Loan Type Master Data</h1>
        <div class="action-group">
            <button type="button" class="btn-action btn-manage" data-bs-toggle="modal" data-bs-target="#addLoanTypeModal">+ Add Loan Type</button>
        </div>
    </div>

    <div class="property-form">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Loan Type Name</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loan_types as $type)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $type->loan_type_name }}</strong></td>
                        <td>
                            <span class="status-badge {{ $type->status == 1 ? 'status-active' : 'status-inactive' }}">
                                {{ $type->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $type->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <div class="action-cell justify-content-end">
                                <button type="button" class="action-btn btn-edit me-2" data-bs-toggle="modal" data-bs-target="#editLoanTypeModal{{ $type->id }}">Edit</button>
                                <a href="{{ route('deleteLoanType', $type->id) }}" class="action-btn btn-delete text-decoration-none" onclick="return confirm('Delete this record?')">Delete</a>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal for each record --}}
                    <div class="modal fade" id="editLoanTypeModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content text-start">
                                <form action="{{ route('updateLoanType', $type->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header"><h5>Edit Loan Type</h5></div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Loan Type Name <span class="req">*</span></label>
                                            <input type="text" name="loan_type_name" class="form-control" value="{{ $type->loan_type_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="1" {{ $type->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $type->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn-save px-4">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">No data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addLoanTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('storeLoanType') }}" method="POST">
                @csrf
                <div class="modal-header"><h5>Add New Loan Type</h5></div>
                <div class="modal-body">
                    <label class="form-label">Loan Type Name <span class="req">*</span></label>
                    <input type="text" name="loan_type_name" class="form-control" placeholder="e.g. House Loan, Personal Loan" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.includes.footer_links')
</body>
</html>