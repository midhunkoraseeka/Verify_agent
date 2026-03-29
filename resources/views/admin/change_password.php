<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Change Password</title>
    <?php include 'includes/header_links.php'; ?>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/header.php'; ?>

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Change Password</h1>

            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">
                    ← Back
                </button>
            </div>
        </div>

        <form class="property-form" id="changePasswordForm" autocomplete="off">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Current Password <span class="req">*</span></label>
                    <input type="password" class="form-control" name="current_password" required placeholder="••••••••" />
                </div>

                <div class="col-md-6">
                    <label class="form-label">New Password <span class="req">*</span></label>
                    <input type="password" class="form-control" name="new_password" required placeholder="New password" />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm New Password <span class="req">*</span></label>
                    <input type="password" class="form-control" name="confirm_password" required placeholder="Confirm new password" />
                </div>

                <div class="col-12">
                    <small class="form-text text-muted">
                        Password must be at least 8 characters long and contain letters, numbers & symbols.
                    </small>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="button" class="btn-cancel" onclick="history.back()">
                    Cancel
                </button>
                <button type="submit" class="btn-save">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <?php include 'includes/footer_links.php'; ?>
</body>
</html>