<style>
    .logo-text {
        color: #F28D3C;
        font-weight: bold;
    }
</style>

<div class="overlay" id="overlay"></div>

<div class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}">
            <h2 class="text-white text-center"> Verify <span class="logo-text">Agent</span></h2>
        </a>
    </div>

    <div class="sidebar-content">

        <a href="{{ route('dashboard') }}" class="nav-link" id="dashboard-link">
            <i class="bi bi-grid-3x3-gap"></i>
            <span>Dashboard</span>
        </a>

        {{-- Master Data --}}
        <a href="#submenu0" class="nav-link dropdown-toggle" id="master-link">
            <i class="bi bi-database-fill-gear"></i>
            <span>Master Data</span>
        </a>
        <div class="submenu" id="submenu0">
            <a href="{{ route('manageFacing') }}" class="nav-link" id="master-facing-link"><span>Facing</span></a>
            <a href="{{ route('manageBhkType') }}" class="nav-link" id="master-bhk-link"><span>BHK Types</span></a>
            <a href="{{ route('manageProjectType') }}" class="nav-link" id="master-project-type-link"><span>Project
                    Types</span></a>
            <a href="{{ route('manageFloor') }}" class="nav-link" id="master-floors-link"><span>Floors</span></a>
            <a href="{{ route('manageParking') }}" class="nav-link" id="master-parking-link"><span>Parking</span></a>
            <a href="{{ route('manageRoadSize') }}" class="nav-link" id="master-road-link"><span>Road Size</span></a>
            <a href="{{ route('manageApproval') }}" class="nav-link" id="master-approval-link"><span>Approved
                    by</span></a>
            <a href="{{ route('manageLandType') }}" class="nav-link" id="master-land-link"><span>Land Type</span></a>
            <a href="{{ route('manageAdType') }}" class="nav-link" id="master-ad-link"><span>Ad type</span></a>
            <a href="{{ route('manageLegalService') }}" class="nav-link" id="master-legal-link"><span>Legal
                    Services</span></a>
            <a href="{{ route('manageVasthuService') }}" class="nav-link" id="master-vasthu-link"><span>Vasthu
                    Services</span></a>
            <a href="{{ route('manageLoanType') }}" class="nav-link" id="master-loan-type-link">
                <span>Types Of Loans</span>
            </a>
            <a href="{{ route('manageSurveyService') }}" class="nav-link" id="master-survey-link">
                <span>Survey Services</span>
            </a>
            <a href="{{ route('manageState') }}" class="nav-link" id="master-state-link">
    <span>State</span>
</a>
            <a href="{{ route('manageDistrict') }}" class="nav-link" id="master-district-link">
    <span>District</span>
</a>

            <a href="{{ route('manageConstituency') }}" class="nav-link" id="master-constituency-link">
    <span>Constituency</span>
</a>
        </div>

        <a href="#submenu9" class="nav-link dropdown-toggle" id="users-link">
            <i class="bi bi-people-fill"></i>
            <span>Users</span>
        </a>
        <div class="submenu" id="submenu9">
            <a href="{{ route('createUser') }}" class="nav-link" id="add-user-link"><span>Add</span></a>
            <a href="{{ route('manageUser') }}" class="nav-link" id="manage-user-link"><span>Manage</span></a>
        </div>

        <a href="#submenu1" class="nav-link dropdown-toggle" id="properties-link">
            <i class="bi bi-building"></i>
            <span>Properties</span>
        </a>
        <div class="submenu" id="submenu1">
            <a href="{{ route('createProperty') }}" class="nav-link" id="add-property-link"><span>Add</span></a>
            <a href="{{ route('manageProperty') }}" class="nav-link" id="manage-property-link"><span>Manage</span></a>
        </div>

        <a href="#submenu2" class="nav-link dropdown-toggle" id="agents-link">
            <i class="bi bi-person-badge"></i>
            <span>Agents</span>
        </a>
        <div class="submenu" id="submenu2">
            <a href="{{ route('createAgent') }}" class="nav-link" id="add-agent-link"><span>Add</span></a>
            <a href="{{ route('manageAgent') }}" class="nav-link" id="manage-agent-link"><span>Manage</span></a>
        </div>

        <a href="#submenu3" class="nav-link dropdown-toggle" id="ads-link">
            <i class="bi bi-megaphone"></i>
            <span>Ads</span>
        </a>
        <div class="submenu" id="submenu3">
            <a href="{{ route('createAd') }}" class="nav-link" id="add-ad-link"><span>Add</span></a>
            <a href="{{ route('manageAds') }}" class="nav-link" id="manage-ad-link"><span>Manage</span></a>
        </div>

        <a href="#" class="nav-link" id="payments-link">
            <i class="bi bi-credit-card"></i>
            <span>Payments</span>
        </a>

        <a href="#submenu6" class="nav-link dropdown-toggle" id="loans-link">
            <i class="bi bi-bank"></i>
            <span>Loans</span>
        </a>
        <div class="submenu" id="submenu6">
            <a href="{{ route('createLoan') }}" class="nav-link" id="add-loan-link"><span>Add</span></a>
            <a href="{{ route('manageLoan') }}" class="nav-link" id="manage-loan-link"><span>Manage</span></a>
        </div>

        <a href="#submenu7" class="nav-link dropdown-toggle" id="surveyors-link">
            <i class="bi bi-map"></i>
            <span>Land Surveyors</span>
        </a>
        <div class="submenu" id="submenu7">
            <a href="{{ route('createSurveyor') }}" class="nav-link" id="add-surveyor-link"><span>Add</span></a>
            <a href="{{ route('manageSurveyor') }}" class="nav-link"
                id="manage-surveyor-link"><span>Manage</span></a>
        </div>

        <a href="#submenu8" class="nav-link dropdown-toggle" id="vasthu-link">
            <i class="bi bi-compass"></i>
            <span>Vasthu Persons</span>
        </a>
        <div class="submenu" id="submenu8">
            <a href="{{ route('createVasthu') }}" class="nav-link" id="add-vasthu-link"><span>Add</span></a>
            <a href="{{ route('manageVasthu') }}" class="nav-link" id="manage-vasthu-link"><span>Manage</span></a>
        </div>

        <a href="#submenu4" class="nav-link dropdown-toggle" id="advocates-link">
            <i class="bi bi-people"></i>
            <span>Advocates List</span>
        </a>
        <div class="submenu" id="submenu4">
            <a href="{{ route('createAdvocate') }}" class="nav-link" id="add-advocate-link"><span>Add</span></a>
            <a href="{{ route('manageAdvocate') }}" class="nav-link"
                id="manage-advocate-link"><span>Manage</span></a>
        </div>

        <a href="#submenu5" class="nav-link dropdown-toggle" id="architectures-link">
            <i class="bi bi-diagram-3"></i>
            <span>Architectures</span>
        </a>
        <div class="submenu" id="submenu5">
            <a href="{{ route('createArchitecture') }}" class="nav-link"
                id="add-architecture-link"><span>Add</span></a>
            <a href="{{ route('manageArchitecture') }}" class="nav-link"
                id="manage-architecture-link"><span>Manage</span></a>
        </div>

        <a href="#" class="nav-link" id="reviews-link">
            <i class="bi bi-star-fill"></i>
            <span>Reviews</span>
        </a>

        <a href="#" class="nav-link" id="settings-link">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>

        <a href="#" class="nav-link" id="change-password-link">
            <i class="bi bi-key"></i>
            <span>Change Password</span>
        </a>

        <a href="{{ route('logout') }}" class="nav-link" id="logout-link"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
            toggle.addEventListener("click", function(e) {
                const href = this.getAttribute('href');

                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    const isOpen = this.classList.contains("active");

                    if (!isOpen) {
                        submenu.classList.add("show");
                        submenu.style.maxHeight = submenu.scrollHeight + "px";
                        this.classList.add("active");
                    } else {
                        submenu.style.maxHeight = "0";
                        this.classList.remove("active");
                        setTimeout(() => submenu.classList.remove("show"), 400);
                    }
                }
            });
        });

        const currentPath = window.location.pathname;

        const pageMapping = [{
                id: "dashboard-link",
                path: "{{ route('dashboard', [], false) }}"
            },

            // Master Data Mappings
            {
                id: "master-facing-link",
                path: "{{ route('manageFacing', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-bhk-link",
                path: "{{ route('manageBhkType', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-project-type-link",
                path: "{{ route('manageProjectType', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-floors-link",
                path: "{{ route('manageFloor', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-parking-link",
                path: "{{ route('manageParking', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-road-link",
                path: "{{ route('manageRoadSize', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-approval-link",
                path: "{{ route('manageApproval', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-land-link",
                path: "{{ route('manageLandType', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-ad-link",
                path: "{{ route('manageAdType', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-legal-link",
                path: "{{ route('manageLegalService', [], false) }}",
                parentId: "master-link"
            },
            {
                id: "master-vasthu-link",
                path: "{{ route('manageVasthuService', [], false) }}",
                parentId: "master-link"
            },

            // Property Mappings
            {
                id: "add-property-link",
                path: "{{ route('createProperty', [], false) }}",
                parentId: "properties-link"
            },
            {
                id: "manage-property-link",
                path: "{{ route('manageProperty', [], false) }}",
                parentId: "properties-link"
            },
            {
                id: "manage-property-link",
                regex: /^\/editProperty\/\d+$/,
                parentId: "properties-link"
            },

            // Users Mapping
            {
                id: "add-user-link",
                path: "{{ route('createUser', [], false) }}",
                parentId: "users-link"
            },
            {
                id: "manage-user-link",
                path: "{{ route('manageUser', [], false) }}",
                parentId: "users-link"
            },
            {
                id: "manage-user-link",
                regex: /^\/editUser\/\d+$/,
                parentId: "users-link"
            },

            // Loans Mapping
            {
                id: "add-loan-link",
                path: "{{ route('createLoan', [], false) }}",
                parentId: "loans-link"
            },
            {
                id: "manage-loan-link",
                path: "{{ route('manageLoan', [], false) }}",
                parentId: "loans-link"
            },
            {
                id: "manage-loan-link",
                regex: /^\/editLoan\/\d+$/,
                parentId: "loans-link"
            },

            // Vasthu Persons Mapping
            {
                id: "add-vasthu-link",
                path: "{{ route('createVasthu', [], false) }}",
                parentId: "vasthu-link"
            },
            {
                id: "manage-vasthu-link",
                path: "{{ route('manageVasthu', [], false) }}",
                parentId: "vasthu-link"
            },
            {
                id: "manage-vasthu-link",
                regex: /^\/editVasthu\/\d+$/,
                parentId: "vasthu-link"
            },

            {
                id: "master-survey-link",
                path: "{{ route('manageSurveyService', [], false) }}",
                parentId: "master-link"
            },
        ];

        pageMapping.forEach(item => {
            let isMatch = false;
            if (item.path && currentPath === item.path) {
                isMatch = true;
            } else if (item.regex && item.regex.test(currentPath)) {
                isMatch = true;
            }

            if (isMatch) {
                const link = document.getElementById(item.id);
                if (link) {
                    link.classList.add("active");
                    if (item.parentId) {
                        const parent = document.getElementById(item.parentId);
                        const submenu = parent?.nextElementSibling;
                        parent?.classList.add("active");
                        if (submenu) {
                            submenu.classList.add("show");
                            submenu.style.maxHeight = submenu.scrollHeight + "px";
                        }
                    }
                }
            }
        });
    });
</script>
