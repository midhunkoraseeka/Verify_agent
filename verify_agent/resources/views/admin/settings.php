<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Settings</title>

    <?php include 'includes/header_links.php'; ?>

    <!-- FLATPICKR CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/header.php'; ?>

    <div class="content">
        <!-- PAGE HEADER -->
        <div class="page-head">
            <h1 class="page-title">Settings</h1>

            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">
                    ← Back
                </button>
            </div>
        </div>

        <div class="property-form">

            <!-- GST / TAX DETAILS -->
            <div class="mb-5">
                <h5 class="fw-semibold mb-4 text-dark">GST / Tax Details</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">GST Number <span class="req">*</span></label>
                        <input type="text" class="form-control" name="gst_number"
                               placeholder="22AAAAA0000A1Z5" required />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">GST Registered Business Name</label>
                        <input type="text" class="form-control" name="gst_business_name"
                               placeholder="Rich Realty Services" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">PAN Number <span class="req">*</span></label>
                        <input type="text" class="form-control" name="pan_number"
                               placeholder="ABCDE1234F" required />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">TAN Number (optional)</label>
                        <input type="text" class="form-control" name="tan_number"
                               placeholder="HYDR12345E" />
                    </div>

                    <!-- FLATPICKR DATE FIELD -->
                    <div class="col-md-4">
                        <label class="form-label">GST Registration Date</label>
                        <input type="text"
                               class="form-control flatpickr-date"
                               name="gst_reg_date"
                               placeholder="Select date" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">GST State Code</label>
                        <input type="text" class="form-control" placeholder="36 - Telangana"  />
                    </div>

                    <!-- GST PERCENTAGE -->
                    <div class="col-md-4">
                        <label class="form-label">
                            GST Percentage (%) <span class="req">*</span>
                        </label>
                        <input type="number"
                               class="form-control"
                               name="gst_percentage"
                               placeholder="18"
                               min="0"
                               max="28"
                               step="0.01"
                               required />
                        <small class="text-muted">
                            Common rates: 5%, 12%, 18%, 28%
                        </small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">GST Certificate / Proof (optional)</label>
                        <input type="file"
                               class="form-control"
                               name="gst_certificate"
                               accept=".pdf,.jpg,.png" />
                        <small class="form-text text-muted">
                            Upload GST certificate or registration proof (PDF/JPG/PNG, max 5MB)
                        </small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn-save">
                        Save GST Details
                    </button>
                </div>
            </div>

            <!-- SUBSCRIPTIONS -->
            <div class="mb-5 pt-4 border-top">
                <h5 class="fw-semibold mb-4 text-dark">Subscriptions</h5>

                <div class="row g-4">

                    <!-- CURRENT PLAN -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header text-white fw-bold"
                                 style="background: linear-gradient(90deg, #f97316, #fb923c);">
                                Current Plan
                            </div>

                            <div class="card-body">
                                <h6 class="fw-bold mb-2">Premium Plan</h6>
                                <p class="small text-muted mb-3">
                                    Monthly billing • ₹ 999 / month
                                </p>

                                <p><strong>Next Billing:</strong> 07 Mar 2026</p>
                                <p>
                                    <strong>Status:</strong>
                                    <span class="badge bg-success">Active</span>
                                </p>

                                <h6 class="fw-bold mt-3 mb-2">Included Features</h6>
                                <ul class="small ps-3 mb-0">
                                    <li>Unlimited properties & agents</li>
                                    <li>Full ad campaign tools</li>
                                    <li>Advocate & architecture management</li>
                                    <li>Priority support & analytics</li>
                                </ul>
                            </div>

                            <div class="card-footer bg-transparent border-0">
                                <button class="btn btn-sm btn-outline-primary w-100">
                                    Upgrade Plan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BILLING & ACTIONS -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light fw-bold">
                                Billing & Actions
                            </div>

                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small">
                                        Change Billing Cycle
                                    </label>
                                    <select class="form-select">
                                        <option>Monthly (₹ 999)</option>
                                        <option>Quarterly (₹ 2,797 – Save 7%)</option>
                                        <option>Yearly (₹ 9,590 – Save 20%)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Auto-Renew</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked />
                                        <label class="form-check-label">
                                            Automatically renew subscription
                                        </label>
                                    </div>
                                </div>

                                <hr class="my-4" />

                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-success flex-fill">
                                        Update Billing
                                    </button>
                                    <button class="btn btn-outline-danger flex-fill">
                                        Cancel Subscription
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PAYMENT HISTORY -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light fw-bold">
                                Recent Payments
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Plan</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>07 Feb 2026</td>
                                                <td>Premium Monthly</td>
                                                <td>₹ 999</td>
                                                <td><span class="badge bg-success">Paid</span></td>
                                                <td><a href="#" class="text-primary small">Download</a></td>
                                            </tr>
                                            <tr>
                                                <td>07 Jan 2026</td>
                                                <td>Premium Monthly</td>
                                                <td>₹ 999</td>
                                                <td><span class="badge bg-success">Paid</span></td>
                                                <td><a href="#" class="text-primary small">Download</a></td>
                                            </tr>
                                            <tr>
                                                <td>07 Dec 2025</td>
                                                <td>Premium Monthly</td>
                                                <td>₹ 999</td>
                                                <td><span class="badge bg-success">Paid</span></td>
                                                <td><a href="#" class="text-primary small">Download</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php include 'includes/footer_links.php'; ?>

    <!-- FLATPICKR JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- FLATPICKR INIT -->
    <script>
        flatpickr(".flatpickr-date", {
            dateFormat: "d-m-Y",
            maxDate: "today",
            allowInput: true
        });
    </script>

</body>
</html>
