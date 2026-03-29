<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reviews</title>
    <?php include 'includes/header_links.php'; ?>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="content">

    <div class="page-head">
        <h1 class="page-title">Reviews</h1>
    </div>

    <div class="property-form">

        <!-- ================= FILTER SECTION ================= -->
        <div class="filter-section mb-4">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" placeholder="Reviewer name or email..." />
                </div>

                <div class="col-md-3">
                    <label class="form-label">Review For</label>
                    <select class="form-select">
                        <option value="">All</option>
                        <option value="agent">Agent</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Rating</label>
                    <select class="form-select">
                        <option value="">All Ratings</option>
                        <option value="5">5 Star</option>
                        <option value="4">4 Star</option>
                        <option value="3">3 Star</option>
                        <option value="2">2 Star</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn-save w-100">Submit</button>
                </div>

            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reviewer</th>
                        <th>Email</th>
                        <th>Review For</th>
                        <th>Agent / User Name</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Posted On</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Sai Reddy</td>
                        <td>sai@gmail.com</td>
                        <td>Agent</td>
                        <td>Ravi Kumar</td>
                        <td>★★★★★</td>
                        <td>Very professional and responsive.</td>
                        <td>10 Feb 2026</td>
                        <td class="action-cell">
                            <button class="action-btn btn-delete">Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Anusha</td>
                        <td>anusha@gmail.com</td>
                        <td>User</td>
                        <td>Anita Sharma</td>
                        <td>★★★★☆</td>
                        <td>Good experience overall.</td>
                        <td>08 Feb 2026</td>
                        <td class="action-cell">
                            <button class="action-btn btn-delete">Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Rahul Mehta</td>
                        <td>rahul@gmail.com</td>
                        <td>Agent</td>
                        <td>Suresh Patel</td>
                        <td>★★★☆☆</td>
                        <td>Average service.</td>
                        <td>02 Feb 2026</td>
                        <td class="action-cell">
                            <button class="action-btn btn-delete">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ================= PAGINATION ================= -->
        <div class="pagination-container mt-3">
            <div class="pagination-info">Showing 1–10 of 18 reviews</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#">Previous</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</div>

<?php include 'includes/footer_links.php'; ?>

</body>
</html>