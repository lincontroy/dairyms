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
            height: 100%;
        }
        
        html {
            height: 100%;
        }
        
        /* Sidebar Styling */
        .sidebar {
            background-color: var(--farm-green);
            color: white;
            height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            transform: translateX(-100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .sidebar-brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            background-color: var(--farm-green);
        }
        
        .sidebar-brand h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-toggle {
            display: block;
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Scrollable navigation container */
        .sidebar-nav-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 1rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            min-height: min-content;
        }
        
        /* Custom scrollbar for sidebar */
        .sidebar-nav-container::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar-nav-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .sidebar-nav-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        
        .sidebar-nav-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1rem;
            margin: 0.125rem 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            flex-shrink: 0;
        }
        
        .sidebar-nav .badge {
            margin-left: auto;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            flex-shrink: 0;
            font-size: 0.75rem;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-nav .section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 1.25rem 1rem 0.5rem;
            padding: 0 0.5rem;
            font-weight: 500;
        }
        
        /* Main Content Area */
        .main-wrapper {
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            width: 100%;
        }
        
        /* Top Navbar */
        .top-navbar {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1040;
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
            display: block;
            background: none;
            border: none;
            color: var(--farm-green);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
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
            flex: 1;
            padding: 0 1rem;
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
            white-space: nowrap;
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
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
                width: var(--sidebar-width);
            }
            
            .main-wrapper {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
            
            .hamburger-btn {
                display: none;
            }
            
            .sidebar-toggle {
                display: none;
            }
            
            .search-form {
                max-width: 400px;
                margin: 0 1.5rem;
            }
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                width: 280px;
            }
            
            .navbar-content {
                flex-wrap: nowrap;
            }
            
            .search-form {
                max-width: 300px;
                margin: 0 1rem;
            }
            
            .page-title {
                font-size: 1.1rem;
                padding: 0 0.5rem;
            }
        }
        
        @media (max-width: 767.98px) {
            .top-navbar {
                padding: 0.75rem 1rem;
            }
            
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
                font-size: 1rem;
                order: 2;
                width: 100%;
                text-align: center;
                margin-top: 0.5rem;
                padding: 0;
            }
            
            .user-dropdown .btn span:last-child {
                display: none;
            }
            
            .breadcrumb {
                font-size: 0.875rem;
            }
            
            .sidebar {
                width: 85%;
                max-width: 280px;
            }
        }
        
        @media (max-width: 575.98px) {
            .hamburger-btn {
                width: 36px;
                height: 36px;
                font-size: 1.25rem;
            }
            
            .user-dropdown .btn {
                padding: 0.25rem 0.5rem;
            }
            
            .sidebar-nav .nav-link {
                padding: 0.625rem 0.875rem;
                margin: 0.125rem 0.375rem;
                font-size: 0.9rem;
            }
            
            .sidebar-nav .nav-link i {
                font-size: 1rem;
                margin-right: 0.625rem;
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
            z-index: 1040;
            display: none;
            backdrop-filter: blur(2px);
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
        
        /* Sidebar footer for user info */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .user-details {
            flex: 1;
            min-width: 0;
        }
        
        .user-name {
            font-size: 0.875rem;
            font-weight: 500;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Prevent body scroll when sidebar is open */
        body.sidebar-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100%;
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
        
        <!-- Scrollable Navigation -->
        <div class="sidebar-nav-container">
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Animal Management Section -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isStaff() || auth()->user()->isVet())
                    <li class="nav-item">
                        <div class="section-title">Animal Management</div>
                    </li>
                    
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager() || auth()->user()->isVet())
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
                    <li class="nav-item">
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
                    </li>
                    @endif
                    
                    <li class="nav-item">
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
                    @endif

                    <!-- Milk Supply Chain Section -->
                    @if(auth()->user()->canManageSuppliers())
                    <li class="nav-item">
                        <div class="section-title">Milk Supply Chain</div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" 
                           href="{{ route('suppliers.index') }}">
                            <i class="fas fa-truck"></i>
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
                            <i class="fas fa-wine-bottle"></i>
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
                            <i class="fas fa-money-bill-wave"></i>
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

                    <!-- Financial Management Section -->
                    @if(auth()->user()->canManageExpenses())
                    <li class="nav-item">
                        <div class="section-title">Financial Management</div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" 
                           href="{{ route('expenses.index') }}">
                            <i class="fas fa-receipt"></i>
                            <span>Expenses</span>
                            @php
                                $pendingExpenses = \App\Models\Expense::pending()->count();
                            @endphp
                            @if($pendingExpenses > 0)
                                <span class="badge">{{ $pendingExpenses }}</span>
                            @endif
                        </a>
                    </li>
                    @endif

                    <!-- Administration Section -->
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <div class="section-title">Administration</div>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                           href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                           href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i>
                            <span>User Management</span>
                            @php
                                $totalUsers = \App\Models\User::count();
                            @endphp
                            @if($totalUsers > 0)
                                <span class="badge">{{ $totalUsers }}</span>
                            @endif
                        </a>
                    </li>
                    @endif

                    <!-- Settings Section -->
                    <li class="nav-item">
                        <div class="section-title">Settings</div>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" 
                           href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        
        <!-- Sidebar Footer with User Info -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>
        </div>
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
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
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
            // Sidebar elements
            const sidebar = document.getElementById('sidebar');
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const body = document.body;
            
            // Toggle sidebar function
            function toggleSidebar() {
                const isOpen = sidebar.classList.contains('show');
                
                if (isOpen) {
                    // Close sidebar
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                    enableBodyScroll();
                } else {
                    // Open sidebar
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    body.classList.add('sidebar-open');
                    disableBodyScroll();
                }
            }
            
            // Disable body scroll
            function disableBodyScroll() {
                body.style.overflow = 'hidden';
                body.style.position = 'fixed';
                body.style.width = '100%';
                body.style.height = '100%';
            }
            
            // Enable body scroll
            function enableBodyScroll() {
                body.style.overflow = '';
                body.style.position = '';
                body.style.width = '';
                body.style.height = '';
            }
            
            // Event listeners
            hamburgerBtn.addEventListener('click', toggleSidebar);
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking on a link (mobile)
            if (window.innerWidth < 992) {
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
            
            // Handle window resize
            function handleResize() {
                if (window.innerWidth >= 992) {
                    // On desktop, ensure sidebar is visible and body scroll is enabled
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                    enableBodyScroll();
                } else {
                    // On mobile, ensure sidebar is hidden by default
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                    enableBodyScroll();
                }
            }
            
            // Initial resize handler
            handleResize();
            
            // Listen for resize events
            window.addEventListener('resize', handleResize);
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Update sidebar badges with live count (optional - can be enabled if needed)
            function updateSidebarCounts() {
                // This function can be used to update counts via AJAX if needed
                // Example: fetch('/api/sidebar-counts').then(...)
            }
        });
        
        // Prevent body scroll when sidebar is open on mobile
        document.addEventListener('touchmove', function(e) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('show')) {
                e.preventDefault();
            }
        }, { passive: false });
    </script>
    
    @stack('scripts')
</body>
</html>