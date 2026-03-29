<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard</title>

@include('admin.includes.header_links')

<style>

/* CARDS - Compact, Premium, 8 Cards Grid */
.insight-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 16px 18px 14px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.07);
  transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
  height: 100%;
  position: relative;
  overflow: hidden;
}

.insight-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #f97316, #fb923c, #f97316);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.insight-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(249,115,22,0.15);
  border-color: #fed7aa;
}

.insight-card:hover::before { opacity: 1; }

.card-top {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 8px;
}

.card-icon {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 17px;
  background: linear-gradient(135deg, #fff7ed, #fed7aa);
  color: #ea580c;
  box-shadow: 0 2px 8px rgba(249,115,22,0.2);
  flex-shrink: 0;
}

.card-label {
  font-size: 10.5px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.7px;
  color: #64748b;
  margin-bottom: 2px;
}

.card-value {
  font-size: 26px;
  font-weight: 800;
  line-height: 1;
  letter-spacing: -0.4px;
  color: #0f172a;
  margin-bottom: 1px;
}

.card-sub {
  font-size: 12px;
  color: #64748b;
  font-weight: 500;
}

.card-progress {
  margin-top: 10px;
}

.card-progress-bar {
  height: 5px;
  background: #e5e7eb;
  border-radius: 5px;
  overflow: hidden;
}

.card-progress-bar span {
  display: block;
  height: 100%;
  background: linear-gradient(90deg, #f97316, #ea580c);
  border-radius: 5px;
  box-shadow: 0 0 8px rgba(249,115,22,0.4);
  transition: width 0.8s ease-out;
}

/* PANELS - Premium Glass-like */
.panel {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  overflow: hidden;
  margin-bottom: 32px;
}

.panel-header {
  padding: 16px 24px;
  background: linear-gradient(135deg, #fef7ee 0%, #fffaf5 100%);
  border-bottom: 1px solid #fed7aa;
  font-size: 16px;
  font-weight: 700;
  color: #c2410c;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}


.panel-header span {
  font-size: 13px;
  font-weight: 500;
  color: #9ca3af;
}

/* TABLES - Striped, Attractive Headers, Status Colors */
.table {
  margin-bottom: 0;
}

.table thead th {
  background: #fefce8;
  color: #92400e;
  font-size: 11.5px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  padding: 16px 20px;
  border: none;
  white-space: nowrap;
  position: relative;
}



.table tbody td {
  padding: 16px 20px;
  font-size: 14px;
  color: #374151;
  border-color: #f1f5f9;
  vertical-align: middle;
}

.table tbody tr:nth-child(even) {
  background: #fdfdfd;
}

.table tbody tr:hover {
  background: linear-gradient(90deg, #fff7ed 0%, #fef3c7 100%);
  transform: scale(1.01);
  box-shadow: inset 0 0 0 1px #fed7aa;
}


/* BADGES - Modern Pills */
.badge-soft {
  padding: 6px 14px;
  font-size: 12.5px;
  font-weight: 600;
  border-radius: 20px;
  border: 1px solid rgba(0,0,0,0.08);
  box-shadow: 0 2px 4px rgba(0,0,0,0.06);
}

.badge-active {
  background: linear-gradient(135deg, #ecfdf5, #d1fae5);
  color: #047857;
}

.badge-pending {
  background: linear-gradient(135deg, #fff7ed, #fed7aa);
  color: #c2410c;
}

.badge-approved {
  background: linear-gradient(135deg, #eef2ff, #e0e7ff);
  color: #4338ca;
}

/* BUTTONS - Subtle Corporate */
.btn-soft {
  padding: 8px 18px;
  font-size: 13px;
  font-weight: 600;
  border-radius: 10px;
  border: 1px solid #d1d5db;
  background: white;
  color: #374151;
  transition: all 0.2s ease;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.btn-soft:hover {
  background: #fff7ed;
  border-color: #f97316;
  color: #ea580c;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(249,115,22,0.2);
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .content { padding: 16px; }
  .insight-card { padding: 14px 16px; }
  .card-value { font-size: 24px; }
  .col-xl-3 { flex: 0 0 100%; max-width: 100%; }
}
</style>
</head>

<body>

@include('admin.includes.sidebar')
@include('admin.includes.header')

<div class="content">
<div class="dashboard-wrapper">

<!-- 8 INSIGHT CARDS - Perfect 4x2 Grid -->
<div class="row g-4 mb-5">
  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Total Properties</div>
          <div class="card-value">248</div>
        </div>
        <div class="card-icon"><i class="bi bi-buildings-fill"></i></div>
      </div>
      <div class="card-sub">+12 this month</div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Active Agents</div>
          <div class="card-value">54</div>
        </div>
        <div class="card-icon"><i class="bi bi-person-badge-fill"></i></div>
      </div>
      <div class="card-sub">42 verified</div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Total Users</div>
          <div class="card-value">1,320</div>
        </div>
        <div class="card-icon"><i class="bi bi-people-fill"></i></div>
      </div>
      <div class="card-sub">1,110 active</div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Open Plots</div>
          <div class="card-value">96</div>
        </div>
        <div class="card-icon"><i class="bi bi-geo-alt-fill"></i></div>
      </div>
      <div class="card-sub">70 available</div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Apartments</div>
          <div class="card-value">142</div>
        </div>
        <div class="card-icon"><i class="bi bi-building-fill"></i></div>
      </div>
      <div class="card-sub">118 occupied</div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Projects</div>
          <div class="card-value">28</div>
        </div>
        <div class="card-icon"><i class="bi bi-diagram-3-fill"></i></div>
      </div>
      <div class="card-sub">21 approved</div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Revenue</div>
          <div class="card-value">₹4.2M</div>
        </div>
        <div class="card-icon"><i class="bi bi-currency-rupee"></i></div>
      </div>
      <div class="card-sub">+18% vs last month</div>
    </div>
  </div>

  <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
    <div class="insight-card">
      <div class="card-top">
        <div>
          <div class="card-label">Bookings</div>
          <div class="card-value">89</div>
        </div>
        <div class="card-icon"><i class="bi bi-calendar3"></i></div>
      </div>
      <div class="card-sub">45 pending</div>
    </div>
  </div>
</div>

<!-- RECENT PROPERTIES -->
<div class="panel">
  <div class="panel-header">
    Recent Properties
    <span>Last 10 added • 2 new today</span>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Property</th>
          <th>Type</th>
          <th>Location</th>
          <th>Status</th>
          <th>Price</th>
          <th>Added</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr class="status-active">
          <td><strong>Green Valley Residency</strong></td>
          <td><span class="badge-soft">Apartment</span></td>
          <td>Bangalore</td>
          <td><span class="badge-soft badge-active">Active</span></td>
          <td>₹85L</td>
          <td>02 Feb 2026</td>
          <td><button class="btn-soft">View</button></td>
        </tr>
        <tr class="status-pending">
          <td><strong>Sunrise Open Plots</strong></td>
          <td><span class="badge-soft">Open Plot</span></td>
          <td>Hyderabad</td>
          <td><span class="badge-soft badge-pending">Pending</span></td>
          <td>₹42L</td>
          <td>01 Feb 2026</td>
          <td><button class="btn-soft">View</button></td>
        </tr>
        <tr class="status-active">
          <td><strong>Elite Heights Tower</strong></td>
          <td><span class="badge-soft">Villa</span></td>
          <td>Chennai</td>
          <td><span class="badge-soft badge-active">Active</span></td>
          <td>₹1.2Cr</td>
          <td>31 Jan 2026</td>
          <td><button class="btn-soft">View</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- 2-COLUMN TABLES -->
<div class="row g-4">
  <div class="col-xl-6">
    <div class="panel">
      <div class="panel-header">
        Agents Activity
        <span>Recent logins • 12 online</span>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Agent</th>
              <th>Email</th>
              <th>Status</th>
              <th>Active</th>
            </tr>
          </thead>
          <tbody>
            <tr class="status-active">
              <td><strong>Ravi Kumar</strong></td>
              <td>ravi@agent.com</td>
              <td><span class="badge-soft badge-active">Verified</span></td>
              <td>10 mins ago</td>
            </tr>
            <tr class="status-pending">
              <td><strong>Anita Sharma</strong></td>
              <td>anita@agent.com</td>
              <td><span class="badge-soft badge-pending">Pending</span></td>
              <td>2 hrs ago</td>
            </tr>
            <tr class="status-active">
              <td><strong>Suresh Patel</strong></td>
              <td>suresh@agent.com</td>
              <td><span class="badge-soft badge-approved">Approved</span></td>
              <td>5 hrs ago</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-xl-6">
    <div class="panel">
      <div class="panel-header">
        Architecture Approvals
        <span>Awaiting action • 3 pending</span>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Project</th>
              <th>Architect</th>
              <th>Status</th>
              <th>Submitted</th>
            </tr>
          </thead>
          <tbody>
            <tr class="status-pending">
              <td><strong>Elite Heights</strong></td>
              <td>Studio Axis</td>
              <td><span class="badge-soft badge-pending">Pending</span></td>
              <td>30 Jan 2026</td>
            </tr>
            <tr class="status-approved">
              <td><strong>Oak Villas</strong></td>
              <td>Design Hub</td>
              <td><span class="badge-soft badge-approved">Approved</span></td>
              <td>28 Jan 2026</td>
            </tr>
            <tr class="status-active">
              <td><strong>Blue Sky Towers</strong></td>
              <td>Urban Works</td>
              <td><span class="badge-soft badge-active">Active</span></td>
              <td>26 Jan 2026</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- NEW: BOOKINGS TABLE -->
<div class="panel mt-4">
  <div class="panel-header">
    Recent Bookings
    <span>Today’s bookings • ₹2.4L revenue</span>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Property</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <tr class="status-approved">
          <td><strong>Priya R.</strong></td>
          <td>Green Valley</td>
          <td>₹25L</td>
          <td><span class="badge-soft badge-approved">Paid</span></td>
          <td>Today</td>
        </tr>
        <tr class="status-pending">
          <td><strong>Rahul M.</strong></td>
          <td>Sunrise Plots</td>
          <td>₹15L</td>
          <td><span class="badge-soft badge-pending">Pending</span></td>
          <td>Today</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</div>
</div>

@include('admin.includes.footer_links'); ?>

</body>
</html>