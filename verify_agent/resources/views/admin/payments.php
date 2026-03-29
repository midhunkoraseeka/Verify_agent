<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Payments</title>
    <?php include 'includes/header_links.php'; ?>

    <style>
        /* Theme color variables – adjust these to match your exact theme */
        :root {
            --theme-orange: #f97316;
            --theme-orange-dark: #ea580c;
            --theme-orange-light: #fed7aa;
            --theme-bg-light: #fff7ed;
        }

        /* Modal – Corporate, neat, theme-aligned */
        #receiptModal .modal-dialog {
            max-width: 500px;
        }

        #receiptModal .modal-content {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        #receiptModal .modal-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 20px;
        }

        #receiptModal .modal-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--theme-orange);
        }

        #receiptModal .modal-body {
            padding: 16px 20px;
        }

        #receiptModal .receipt-logo {
            font-size: 2rem;
            color: var(--theme-orange);
            margin-bottom: 8px;
        }

        #receiptModal .receipt-info {
            font-size: 0.9rem;
            line-height: 1.45;
        }

        #receiptModal .receipt-info strong {
            min-width: 130px;
            display: inline-block;
            color: #0f172a;
        }

        #receiptModal .receipt-note {
            font-size: 0.85rem;
            color: #475569;
            background: var(--theme-bg-light);
            padding: 10px 14px;
            border-radius: 6px;
            border: 1px solid var(--theme-orange-light);
            margin-top: 12px;
            text-align: center;
        }

        #receiptModal .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 10px 20px;
            background: #f8fafc;
        }

        /* Corporate & neat buttons – theme aligned */
        #receiptModal .btn-modal {
            padding: 7px 20px;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
        }

        #receiptModal .btn-close-receipt {
            background: white;
            border: 1px solid #cbd5e1;
            color: #475569;
        }

        #receiptModal .btn-close-receipt:hover {
            background: #f1f5f9;
            border-color: var(--theme-orange);
            color: var(--theme-orange);
        }

        #receiptModal .btn-download {
            background: var(--theme-orange);
            color: white;
            border: none;
        }

        #receiptModal .btn-download:hover {
            background: var(--theme-orange-dark);
        }
    </style>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/header.php'; ?>

    <div class="content">
        <div class="page-head">
            <h1 class="page-title">Manage Payments</h1>

            <div class="action-group">
                <button type="button" class="btn-action btn-back" onclick="history.back()">
                    ← Back
                </button>
            </div>
        </div>

        <div class="property-form">

            <!-- Filter & Export Section -->
            <div class="filter-section mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Search Payment</label>
                        <input type="text" class="form-control" placeholder="Txn ID, name or email..." id="searchInput" />
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option>Success</option>
                            <option>Failed</option>
                            <option>Pending</option>
                            <option>Refunded</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Gateway</label>
                        <select class="form-select" id="gatewayFilter">
                            <option value="">All Gateways</option>
                            <option>Razorpay</option>
                            <option>PayU</option>
                            <option>PhonePe</option>
                            <option>Stripe</option>
                        </select>
                    </div>

                    <div class="col-md-5 d-flex gap-2 align-items-end">
                        <button class="btn-save" id="applyFilter">Submit</button>
                        <button class="btn btn-outline-primary" id="exportCSV">Export CSV</button>
                        <button class="btn btn-outline-secondary" id="exportPDF">Export PDF</button>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions & Table -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="bulk-actions d-flex gap-3 align-items-center">
                    <select class="form-select form-select-sm" id="bulkAction" style="width: auto;">
                        <option>Bulk Actions</option>
                        <option value="refund">Refund Selected</option>
                        <option value="success">Mark as Success</option>
                        <option value="failed">Mark as Failed</option>
                        <option value="pending">Mark as Pending</option>
                    </select>
                    <button class="btn btn-sm btn-primary" id="applyBulk">Apply</button>
                </div>
                <div class="text-muted small" id="selectedCount">0 selected</div>
            </div>

            <div class="table-responsive">
                <table class="table" id="paymentsTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>Txn ID</th>
                            <th>User / Agent</th>
                            <th>Plan / Item</th>
                            <th>Amount (₹)</th>
                            <th>Gateway</th>
                            <th>Invoice ID</th>
                            <th>Refund Reason</th>
                            <th>Status</th>
                            <th>Date & Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="row-checkbox"></td>
                            <td>1</td>
                            <td>TXN-9876543210</td>
                            <td>Ravi Kumar (Agent)</td>
                            <td>Premium Monthly</td>
                            <td>999.00</td>
                            <td>Razorpay</td>
                            <td>INV-20260207-001</td>
                            <td>—</td>
                            <td><span class="status-badge status-active">Success</span></td>
                            <td>07 Feb 2026 10:15 AM</td>
                            <td class="action-cell">
                                <button class="action-btn btn-view" data-bs-toggle="modal" data-bs-target="#receiptModal" data-txn="TXN-9876543210">View Receipt</button>
                                <button class="action-btn btn-delete">Refund</button>
                            </td>
                        </tr>

                        
                        <tr>
                            <td><input type="checkbox" class="row-checkbox"></td>
                            <td>2</td>
                            <td>TXN-1234567890</td>
                            <td>Vikas Patel (Admin)</td>
                            <td>Yearly Premium</td>
                            <td>9,590.00</td>
                            <td>Stripe</td>
                            <td>INV-20260201-002</td>
                            <td>—</td>
                            <td><span class="status-badge status-active">Success</span></td>
                            <td>01 Feb 2026 14:30 PM</td>
                            <td class="action-cell">
                                <button class="action-btn btn-view" data-bs-toggle="modal" data-bs-target="#receiptModal" data-txn="TXN-1234567890">View Receipt</button>
                                <button class="action-btn btn-delete">Refund</button>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="row-checkbox"></td>
                            <td>3</td>
                            <td>TXN-4567891230</td>
                            <td>Anita Sharma (Agent)</td>
                            <td>Premium Monthly</td>
                            <td>999.00</td>
                            <td>PayU</td>
                            <td>INV-20260205-003</td>
                            <td>Payment gateway timeout</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>05 Feb 2026 09:45 AM</td>
                            <td class="action-cell">
                                <button class="action-btn btn-view" data-bs-toggle="modal" data-bs-target="#receiptModal" data-txn="TXN-4567891230">View Details</button>
                                <button class="action-btn btn-delete">Cancel</button>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="row-checkbox"></td>
                            <td>4</td>
                            <td>TXN-7891234560</td>
                            <td>Suresh Patel (Agent)</td>
                            <td>Premium Monthly</td>
                            <td>999.00</td>
                            <td>PhonePe</td>
                            <td>INV-20260203-004</td>
                            <td>Requested by user</td>
                            <td><span class="status-badge status-sold">Refunded</span></td>
                            <td>03 Feb 2026 16:20 PM</td>
                            <td class="action-cell">
                                <button class="action-btn btn-view" data-bs-toggle="modal" data-bs-target="#receiptModal" data-txn="TXN-7891234560">View Refund</button>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="row-checkbox"></td>
                            <td>5</td>
                            <td>TXN-2345678901</td>
                            <td>Priya Reddy (Client)</td>
                            <td>Property Booking Fee</td>
                            <td>25,000.00</td>
                            <td>Razorpay</td>
                            <td>INV-20260208-005</td>
                            <td>—</td>
                            <td><span class="status-badge status-active">Success</span></td>
                            <td>08 Feb 2026 11:40 AM</td>
                            <td class="action-cell">
                                <button class="action-btn btn-view" data-bs-toggle="modal" data-bs-target="#receiptModal" data-txn="TXN-2345678901">View Receipt</button>
                                <button class="action-btn btn-delete">Refund</button>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" class="row-checkbox"></td>
                            <td>6</td>
                            <td>TXN-3456789012</td>
                            <td>Mohan Rao (Agent)</td>
                            <td>Quarterly Premium</td>
                            <td>2,797.00</td>
                            <td>PayU</td>
                            <td>INV-20260204-006</td>
                            <td>—</td>
                            <td><span class="status-badge status-active">Success</span></td>
                            <td>04 Feb 2026 15:20 PM</td>
                            <td class="action-cell">
                                <button class="action-btn btn-view" data-bs-toggle="modal" data-bs-target="#receiptModal" data-txn="TXN-3456789012">View Receipt</button>
                                <button class="action-btn btn-delete">Refund</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container mt-3">
                <div class="pagination-info">Showing 1–10 of 68 payments</div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Corporate & Neat Receipt Modal – Theme-aligned -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
            <div class="modal-content border-0 rounded-3 shadow">
                <div class="modal-header bg-white border-bottom py-3 px-4">
                    <h5 class="modal-title fw-semibold" id="receiptModalLabel">
                        Payment Receipt
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-4">
                    <div class="text-center mb-3">
                        <div class="receipt-logo mb-2">
                            <i class="bi bi-building fs-2" style="color: var(--theme-orange);"></i>
                        </div>
                        <h5 class="fw-bold mb-1"> Dashboard</h5>
                        <small class="text-muted">Hyderabad, Telangana</small>
                    </div>

                    <div class="receipt-info small">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Transaction ID:</strong>
                            <span id="modalTxnId">TXN-XXXXXX</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Date & Time:</strong>
                            <span>07 Feb 2026 10:15 AM</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>User / Agent:</strong>
                            <span>Ravi Kumar</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Plan / Item:</strong>
                            <span>Premium Monthly</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Amount:</strong>
                            <span class="fw-bold" style="color: var(--theme-orange);">₹ 999.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Gateway:</strong>
                            <span>Razorpay</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Invoice ID:</strong>
                            <span>INV-20260207-001</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Payment Method:</strong>
                            <span>UPI (GPay)</span>
                        </div>
                    </div>

                    <div class="receipt-note mt-3 p-3 bg-light border rounded text-center small">
                        Thank you for your payment.<br>
                        Your subscription is active until next billing date.<br>
                        Support: support@gmail.com
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn-modal btn-close-receipt px-4" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn-modal btn-download px-4">
                        <i class="bi bi-download me-1"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer_links.php'; ?>

    <script>
        // Fill modal data (demo)
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', function() {
                const txn = this.getAttribute('data-txn') || 'TXN-XXXXXX';
                document.getElementById('modalTxnId').textContent = txn;
            });
        });


    </script>
</body>

</html>