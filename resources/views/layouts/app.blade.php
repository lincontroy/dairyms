<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dairy Farm Management')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --farm-green: #2E7D32;
            --farm-green-light: #4CAF50;
            --sidebar-width: 250px;
        }
        
        * {
            transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Sidebar Styling */
        .sidebar {
            background-color: var(--farm-green);
            color: white;
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transform: translateX(0);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar-brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-brand h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 500;
        }
        
        .sidebar-nav .nav-link i {
            width: 1.5rem;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .sidebar-nav .badge {
            margin-left: auto;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        /* Main Content Area */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .sidebar.collapsed ~ .main-wrapper {
            margin-left: 0;
        }
        
        /* Top Navbar */
        .top-navbar {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        
        /* Hamburger Menu Button */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            color: var(--farm-green);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        
        .hamburger-btn:hover {
            background-color: rgba(46, 125, 50, 0.1);
        }
        
        /* Page Title */
        .page-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }
        
        .page-title i {
            color: var(--farm-green);
        }
        
        /* Search Bar */
        .search-form {
            flex-grow: 1;
            max-width: 400px;
            margin: 0 1.5rem;
        }
        
        .search-form .input-group {
            border-radius: 0.375rem;
            overflow: hidden;
        }
        
        .search-form .form-control {
            border: 1px solid #dee2e6;
            border-right: none;
        }
        
        .search-form .form-control:focus {
            box-shadow: none;
            border-color: var(--farm-green);
        }
        
        .search-form .btn {
            background-color: var(--farm-green);
            border-color: var(--farm-green);
            color: white;
        }
        
        .search-form .btn:hover {
            background-color: #1B5E20;
            border-color: #1B5E20;
        }
        
        /* User Dropdown */
        .user-dropdown .btn {
            background: none;
            border: 1px solid #dee2e6;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
        }
        
        .user-dropdown .btn:hover {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            min-width: 200px;
        }
        
        .user-dropdown .dropdown-item {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .user-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .user-dropdown .dropdown-item.text-danger:hover {
            background-color: #f8d7da;
        }
        
        /* Main Content */
        .main-content {
            padding: 1.5rem;
            min-height: calc(100vh - 70px);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
            padding: 0.75rem 0;
            margin-bottom: 1rem;
        }
        
        .breadcrumb-item a {
            color: var(--farm-green);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #6c757d;
        }
        
        /* Cards */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Buttons */
        .btn-success {
            background-color: var(--farm-green);
            border-color: var(--farm-green);
        }
        
        .btn-success:hover {
            background-color: #1B5E20;
            border-color: #1B5E20;
        }
        
        .btn-outline-success {
            color: var(--farm-green);
            border-color: var(--farm-green);
        }
        
        .btn-outline-success:hover {
            background-color: var(--farm-green);
            border-color: var(--farm-green);
            color: white;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .alert i {
            margin-right: 0.5rem;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
            
            .hamburger-btn {
                display: block;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .search-form {
                max-width: 300px;
                margin: 0 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-content {
                flex-wrap: wrap;
            }
            
            .search-form {
                order: 3;
                max-width: 100%;
                margin: 1rem 0 0 0;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 576px) {
            .top-navbar {
                padding: 0.75rem 1rem;
            }
            
            .user-dropdown .btn span:last-child {
                display: none;
            }
            
            .breadcrumb {
                font-size: 0.875rem;
            }
        }
        
        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        /* Animation for sidebar */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
        
        .sidebar.show {
            animation: slideIn 0.3s ease;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @auth
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-cow me-2"></i>Dairy Farm
                </h5>
                <small class="text-white-50">Management System</small>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Navigation -->
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
             
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            

                @if(auth()->user()->isAdmin())
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('animals.*') ? 'active' : '' }}" 
                       href="{{ route('animals.index') }}">
                        <i class="fas fa-cow"></i>
                        <span>Animal Registry</span>
                        <span class="badge">{{ \App\Models\Animal::count() }}</span>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                   <!-- To this: -->
<a class="nav-link {{ request()->routeIs('breeding-records.*') ? 'active' : '' }}" 
    href="{{ route('breeding-records.index') }}">
     <i class="fas fa-dna"></i>
     <span>Breeding Records</span>
     @php
         $pregnantCount = \App\Models\BreedingRecord::where('pregnancy_result', true)
             ->whereNull('actual_calving_date')
             ->count();
     @endphp
     @if($pregnantCount > 0)
         <span class="badge">{{ $pregnantCount }}</span>
     @endif
 </a>
                </li>
                @if(auth()->user()->isAdmin() || auth()->user()->isStaff() || auth()->user()->isManager())
                
               <!-- With this: -->
<a class="nav-link {{ request()->routeIs('milk-production.*') ? 'active' : '' }}" 
    href="{{ route('milk-production.index') }}">
     <i class="fas fa-wine-bottle"></i>
     <span>Milk Production</span>
     @php
         $todayCount = \App\Models\MilkProduction::today()->count();
     @endphp
     @if($todayCount > 0)
         <span class="badge">{{ $todayCount }}</span>
     @endif
 </a>
                @endif
                <li class="nav-item">
                   <!-- To this: -->
<a class="nav-link {{ request()->routeIs('health-records.*') ? 'active' : '' }}" 
    href="{{ route('health-records.index') }}">
     <i class="fas fa-heartbeat"></i>
     <span>Health Records</span>
     @php
         $activeIssues = \App\Models\HealthRecord::where('outcome', 'Under Treatment')->count();
     @endphp
     @if($activeIssues > 0)
         <span class="badge">{{ $activeIssues }}</span>
     @endif
 </a>
                </li>
                
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <div class="nav-link text-white-50 small text-uppercase mt-3 mb-1">
                        Administration
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                       href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                @endif

                <!-- Add this in the sidebar after other menu items -->
@if(auth()->user()->canManageSuppliers())
<li class="nav-item">
    <div class="nav-link text-white-50 small text-uppercase mt-3 mb-1">
        Milk Supply Chain
    </div>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" 
       href="{{ route('suppliers.index') }}">
        <i class="fas fa-truck me-2"></i>
        <span>Suppliers</span>
        @php
            $activeSuppliers = \App\Models\Supplier::active()->count();
        @endphp
        @if($activeSuppliers > 0)
            <span class="badge">{{ $activeSuppliers }}</span>
        @endif
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('milk-supplies.*') ? 'active' : '' }}" 
       href="{{ route('milk-supplies.index') }}">
        <i class="fas fa-wine-bottle me-2"></i>
        <span>Milk Supplies</span>
        @php
            $todaySupplies = \App\Models\MilkSupply::today()->count();
        @endphp
        @if($todaySupplies > 0)
            <span class="badge">{{ $todaySupplies }}</span>
        @endif
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" 
       href="{{ route('payments.index') }}">
        <i class="fas fa-money-bill-wave me-2"></i>
        <span>Payments</span>
        @php
            $pendingPayments = \App\Models\SupplierPayment::pending()->count();
        @endphp
        @if($pendingPayments > 0)
            <span class="badge">{{ $pendingPayments }}</span>
        @endif
    </a>
</li>
@endif
                
              

                
                <li class="nav-item mt-3">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" 
                       href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-cog"></i>
                        <span>Profile Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="navbar-content">
                <!-- Hamburger Button -->
                <button class="hamburger-btn" id="hamburgerBtn">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Page Title -->
                <h4 class="page-title mb-0">
                    <i class="fas fa-cow me-2"></i>
                    @yield('page-title', 'Dashboard')
                </h4>
                
                <!-- Search Form -->
                <form class="search-form" action="{{ route('animals.search') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" 
                               placeholder="Search animals..." 
                               name="q" value="{{ request('q') }}">
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button class="btn dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    @yield('breadcrumbs')
                </ol>
            </nav>
            
            <!-- Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </main>
    </div>
    @else
        <!-- Guest Layout -->
        @yield('content')
    @endauth
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle functionality
            const sidebar = document.getElementById('sidebar');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }
            
            hamburgerBtn.addEventListener('click', toggleSidebar);
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking on a link (mobile)
            if (window.innerWidth <= 992) {
                document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        if (sidebar.classList.contains('show')) {
                            toggleSidebar();
                        }
                    });
                });
            }
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Confirm delete with better message
            window.confirmDelete = function(event, name = 'this record') {
                if (!confirm(`Are you sure you want to delete ${name}? This action cannot be undone.`)) {
                    event.preventDefault();
                    return false;
                }
                return true;
            }
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    toggleSidebar();
                }
            });
            
            // Update sidebar badge with live count
            function updateAnimalCount() {
                fetch('/api/animals/count')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.sidebar-nav .nav-link[href*="animals"] .badge');
                        if (badge) {
                            badge.textContent = data.count || 0;
                        }
                    })
                    .catch(error => console.error('Error fetching animal count:', error));
            }
            
            // Update count on page load (you can add this API endpoint if needed)
            // updateAnimalCount();
        });
        
        // Responsive sidebar on window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth > 992) {
                // On desktop, ensure sidebar is visible
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>