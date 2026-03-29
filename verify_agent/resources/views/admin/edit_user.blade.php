<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit User: {{ $user->first_name }}</title>
    @include('admin.includes.header_links')
    
    <style>
        .is-invalid {
            border: 1px solid #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
        }
        .error-text { width: 100%; margin-top: 0.35rem; font-size: 0.85rem; color: #dc3545; font-weight: 500; display: block; }
        .col-md-4, .col-md-6, .col-12 { margin-bottom: 10px; }
    </style>
</head>

<body>
@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
    <div class="page-head">
        <h1 class="page-title">Edit User: {{ $user->first_name }} {{ $user->last_name }}</h1>
        <div class="action-group">
            <button type="button" class="btn-action btn-back" onclick="history.back()">← Back</button>
            <a href="{{ route('manageUser') }}" class="btn-action btn-manage">Manage Users</a>
        </div>
    </div>

    <form class="property-form" action="{{ route('updateUser', $user->id) }}" method="POST" id="editUserForm" autocomplete="off">
        @csrf

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">First Name <span class="req">*</span></label>
                <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Last Name <span class="req">*</span></label>
                <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Email Address <span class="req">*</span></label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Phone Number <span class="req">*</span></label>
                <input type="tel" class="form-control" name="phone" maxlength="10" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"
                    value="{{ old('phone', $user->mobile_number) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" class="form-control" name="city" value="{{ old('city', $user->city) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Constituency</label>
                <input type="text" class="form-control" name="constituency" value="{{ old('constituency', $user->constituency) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">PIN Code</label>
                <input type="text" class="form-control" name="pincode" value="{{ old('pincode', $user->pincode) }}">
            </div>

            <div class="col-12">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Username <span class="req">*</span></label>
                <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Password (Leave blank to keep current)</label>
                <input type="password" class="form-control" name="password" placeholder="••••••••">
            </div>
        </div>

        <div class="form-actions mt-4">
            <button type="button" class="btn-cancel" onclick="window.location='{{ route('manageUser') }}'">Cancel</button>
            <button type="submit" class="btn-save">Update User</button>
        </div>
    </form>
</div>

@include('admin.includes.footer_links')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const errors = @json($errors->toArray());
        Object.keys(errors).forEach(fieldName => {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-text';
                errorDiv.innerText = errors[fieldName][0];
                const container = input.closest('.col-md-4, .col-md-6, .col-12');
                if (container) { container.appendChild(errorDiv); }
                const clearFn = () => {
                    input.classList.remove('is-invalid');
                    const msg = container ? container.querySelector('.error-text') : null;
                    if (msg) msg.remove();
                };
                input.addEventListener('input', clearFn);
            }
        });
    });
</script>
</body>
</html>