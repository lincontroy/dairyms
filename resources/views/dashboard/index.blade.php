@extends('layouts.app')

@section('title', 'Dashboard - Dairy Farm Management')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-2">
                                <i class="fas fa-tachometer-alt me-2"></i>Farm Dashboard
                            </h4>
                            <p class="card-text mb-0">
                                Welcome back, {{ auth()->user()->name }}!
                                <span class="badge bg-light text-success ms-2">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <h5 class="mb-0">{{ now()->format('F j, Y') }}</h5>
                            <small>{{ now()->format('l') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this alert -->
@if(auth()->user()->canApprovePayments())
@php
    $autoPendingPayments = \App\Models\SupplierPayment::where('notes', 'like', 'Auto-generated payment%')
        ->where('status', 'pending')
        ->count();
@endphp
@if($autoPendingPayments > 0)
<div class="alert alert-info mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-money-bill-wave me-2"></i>
            {{ $autoPendingPayments }} auto-created payments pending approval
        </div>
        <a href="{{ route('payments.index') }}?status=pending" class="btn btn-sm btn-info">
            Review Payments
        </a>
    </div>
</div>
@endif
@endif

    <!-- Role-Based Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Admin Actions -->
                        @if(auth()->user()->isAdmin())
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('suppliers.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-truck fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Add Supplier</h6>
                                    <small class="text-muted">New milk buyer</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('payments.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Create Payment</h6>
                                    <small class="text-muted">Pay supplier</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('animals.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-cow fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Register Animal</h6>
                                    <small class="text-muted">Add new animal</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('reports.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-chart-bar fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="card-title mb-0">View Reports</h6>
                                    <small class="text-muted">Farm analytics</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('payments.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-check-circle fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Approve Payments</h6>
                                    <small class="text-muted">Review pending</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('users.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-users fa-2x text-secondary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Manage Users</h6>
                                    <small class="text-muted">User management</small>
                                </div>
                            </a>
                        </div>
                        @endif

                        <!-- Manager Actions -->
                        @if(auth()->user()->isManager())
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('milk-supplies.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-wine-bottle fa-2x text-success"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Record Supply</h6>
                                    <small class="text-muted">Milk to supplier</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('suppliers.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-truck fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Add Supplier</h6>
                                    <small class="text-muted">New milk buyer</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('milk-production.quick-entry') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-wine-bottle fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Record Milk</h6>
                                    <small class="text-muted">Daily production</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('animals.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-cow fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Register Animal</h6>
                                    <small class="text-muted">Add new animal</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('breeding-records.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-dna fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Record Breeding</h6>
                                    <small class="text-muted">Breeding record</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('health-records.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-heartbeat fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Health Record</h6>
                                    <small class="text-muted">Animal health</small>
                                </div>
                            </a>
                        </div>
                        @endif

                        <!-- Staff Actions -->
                        @if(auth()->user()->isStaff())
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('milk-production.quick-entry') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-wine-bottle fa-2x text-success"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Record Milk</h6>
                                    <small class="text-muted">Daily production</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('health-records.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-heartbeat fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Health Record</h6>
                                    <small class="text-muted">Animal health</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('animals.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-cow fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-0">View Animals</h6>
                                    <small class="text-muted">Browse animals</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('milk-production.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-list fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Milk Records</h6>
                                    <small class="text-muted">View production</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('health-records.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-clipboard-list fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Health Records</h6>
                                    <small class="text-muted">View treatments</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('breeding-records.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-baby fa-2x text-secondary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Breeding Records</h6>
                                    <small class="text-muted">Pregnancy status</small>
                                </div>
                            </a>
                        </div>
                        @endif

                        <!-- Vet Actions -->
                        @if(auth()->user()->isVet())
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('health-records.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-heartbeat fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Health Record</h6>
                                    <small class="text-muted">Record treatment</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('health-records.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-clipboard-list fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Health Issues</h6>
                                    <small class="text-muted">Active treatments</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('breeding-records.create') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-dna fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Record Breeding</h6>
                                    <small class="text-muted">Artificial insemination</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('animals.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-cow fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-0">View Animals</h6>
                                    <small class="text-muted">Animal registry</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="{{ route('breeding-records.index') }}" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-baby fa-2x text-success"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Pregnant Cows</h6>
                                    <small class="text-muted">Pregnancy status</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-2 col-sm-4 col-6">
                            <a href="#" class="card text-center text-decoration-none border">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="fas fa-syringe fa-2x text-secondary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Vaccinations</h6>
                                    <small class="text-muted">Vaccine schedule</small>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Animals</h6>
                            <h3 class="mb-0">{{ $stats['totalAnimals'] }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-cow fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Today's Milk (L)</h6>
                            <h3 class="mb-0">{{ number_format($stats['totalMilkToday'], 1) }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-wine-bottle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Suppliers</h6>
                            <h3 class="mb-0">{{ $milkSupplyStats['totalSuppliers'] }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-truck fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pending Payments</h6>
                            <h3 class="mb-0">KSh {{ number_format($milkSupplyStats['pendingPaymentsAmount'], 0) }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Milk Production & Supply -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-balance-scale text-primary me-2"></i>Today's Milk Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 text-center">
                            <h2 class="text-success">{{ number_format($stats['totalMilkToday'], 1) }} L</h2>
                            <p class="text-muted mb-0">Total Produced</p>
                        </div>
                        <div class="col-md-6 text-center">
                            <h2 class="text-primary">{{ number_format($milkSupplyStats['todaySupplied'], 1) }} L</h2>
                            <p class="text-muted mb-0">Supplied to Buyers</p>
                            @if($milkSupplyStats['todayWaste'] > 0)
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ number_format($milkSupplyStats['todayWaste'], 1) }}L wasted
                                </small>
                            @endif
                        </div>
                    </div>
                    
                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $soldPercentage = $stats['totalMilkToday'] > 0 ? 
                                ($milkSupplyStats['todaySupplied'] / $stats['totalMilkToday']) * 100 : 0;
                            $wastePercentage = $stats['totalMilkToday'] > 0 ? 
                                ($milkSupplyStats['todayWaste'] / $stats['totalMilkToday']) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $soldPercentage }}%">
                            {{ number_format($soldPercentage, 1) }}% Sold
                        </div>
                        @if($wastePercentage > 0)
                        <div class="progress-bar bg-danger" style="width: {{ $wastePercentage }}%">
                            {{ number_format($wastePercentage, 1) }}% Waste
                        </div>
                        @endif
                    </div>
                    
                    <div class="text-center mt-3">
                        <h4 class="text-success">Revenue Today: KSh {{ number_format($milkSupplyStats['todayRevenue'], 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie text-warning me-2"></i>Financial Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Revenue (This Month)</span>
                            <span class="badge bg-success">KSh {{ number_format($milkSupplyStats['monthRevenue'], 0) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Average Price/Liter</span>
                            <span class="badge bg-info">KSh {{ number_format($milkSupplyStats['avgMilkPrice'], 2) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Total Balance Due</span>
                            <span class="badge bg-danger">KSh {{ number_format($milkSupplyStats['totalBalanceDue'], 0) }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Pending Approvals</span>
                            <span class="badge bg-warning">{{ $milkSupplyStats['pendingPaymentsCount'] }} payments</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Active Suppliers</span>
                            <span class="badge bg-primary">{{ $milkSupplyStats['totalSuppliers'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mb-4">
        <!-- Recent Animals -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-cow text-success me-2"></i>Recent Animals
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($recentAnimals as $animal)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $animal->animal_id }}</h6>
                                    <small>{{ $animal->name ?? 'Unnamed' }}</small>
                                </div>
                                <span class="badge bg-{{ $animal->status === 'lactating' ? 'success' : 'info' }}">
                                    {{ $animal->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('animals.index') }}" class="btn btn-sm btn-outline-success">View All Animals</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Milk Supplies -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-wine-bottle text-primary me-2"></i>Recent Milk Supplies
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($recentMilkSupplies as $supply)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $supply->supplier->name }}</h6>
                                    <small>{{ number_format($supply->quantity_liters, 1) }}L</small>
                                </div>
                                <div class="text-end">
                                    <div>KSh {{ number_format($supply->total_amount, 0) }}</div>
                                    <small class="text-muted">{{ $supply->date->format('M d') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('milk-supplies.index') }}" class="btn btn-sm btn-outline-primary">View All Supplies</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Health Issues -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-heartbeat text-danger me-2"></i>Recent Health Issues
                    </h6>
                </div>
                <div class="card-body">
                    @if($recentHealth->count() > 0)
                        <div class="list-group">
                            @foreach($recentHealth as $record)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $record->animal->animal_id ?? 'Unknown' }}</h6>
                                        <small>{{ $record->diagnosis }}</small>
                                    </div>
                                    <span class="badge bg-danger">Treatment</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                            <h6>No Health Issues</h6>
                            <p class="text-muted mb-0">All animals are healthy</p>
                        </div>
                    @endif
                    <div class="text-center mt-3">
                        <a href="{{ route('health-records.index') }}" class="btn btn-sm btn-outline-danger">View Health Records</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Tasks & Top Suppliers -->
    <div class="row">
        <!-- Pending Tasks -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-tasks text-warning me-2"></i>Pending Tasks
                    </h6>
                </div>
                <div class="card-body">
                    @if(auth()->user()->canApprovePayments())
                    @if($pendingTasks['pendingMilkSupplies'] > 0)
                    <div class="alert alert-warning mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ $pendingTasks['pendingMilkSupplies'] }} milk supplies need approval
                            </div>
                            <a href="{{ route('milk-supplies.index') }}?status=recorded" class="btn btn-sm btn-warning">Review</a>
                        </div>
                    </div>
                    @endif
                    
                    @if($pendingTasks['pendingPayments'] > 0)
                    <div class="alert alert-danger mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-money-bill-wave me-2"></i>
                                {{ $pendingTasks['pendingPayments'] }} payments need approval
                            </div>
                            <a href="{{ route('payments.index') }}?status=pending" class="btn btn-sm btn-danger">Review</a>
                        </div>
                    </div>
                    @endif
                    @endif
                    
                    @if($pendingTasks['activeHealthIssues'] > 0)
                    <div class="alert alert-danger mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-heartbeat me-2"></i>
                                {{ $pendingTasks['activeHealthIssues'] }} animals under treatment
                            </div>
                            <a href="{{ route('health-records.index') }}" class="btn btn-sm btn-danger">View</a>
                        </div>
                    </div>
                    @endif
                    
                    @if($pendingTasks['lowActivitySuppliers'] > 0)
                    <div class="alert alert-info mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-truck me-2"></i>
                                {{ $pendingTasks['lowActivitySuppliers'] }} suppliers with low activity
                            </div>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-info">Check</a>
                        </div>
                    </div>
                    @endif
                    
                    @if($stats['pregnantCows'] > 0)
                    <div class="alert alert-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-baby me-2"></i>
                                {{ $stats['pregnantCows'] }} pregnant cows
                            </div>
                            <a href="{{ route('breeding-records.index') }}" class="btn btn-sm btn-success">Monitor</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Top Suppliers -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-truck text-info me-2"></i>Top Suppliers (This Month)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                      <!-- In the Top Suppliers section -->
@foreach($topSuppliers as $supplier)
<div class="list-group-item">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0">{{ $supplier->name }}</h6>
            @php
                $monthSupplied = $supplier->milkSupplies->sum('quantity_liters');
                $monthAmount = $supplier->milkSupplies->sum('total_amount');
                $totalPaid = $supplier->payments->sum('amount');
                $balance = $monthAmount - $totalPaid;
            @endphp
            <small>{{ number_format($monthSupplied, 1) }}L supplied</small>
        </div>
        <div class="text-end">
            <div>KSh {{ number_format($monthAmount, 0) }}</div>
            @if($balance > 0)
                <small class="text-danger">Due: KSh {{ number_format($balance, 0) }}</small>
            @else
                <small class="text-success">Paid</small>
            @endif
        </div>
    </div>
</div>
@endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-info">View All Suppliers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: 1px solid #dee2e6;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 0.75rem 0;
    }
    
    .list-group-item:first-child {
        border-top: none;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .progress {
        border-radius: 5px;
    }
    
    .alert {
        border-radius: 5px;
        margin-bottom: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple chart for milk production (if you want to add later)
    console.log('Dashboard loaded');
});
</script>
@endpush
@endsection