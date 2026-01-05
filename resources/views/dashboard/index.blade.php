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

    <!-- Quick Actions -->
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
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('animals.create') }}" class="card quick-action-card text-center text-decoration-none h-100">
                                <div class="card-body">
                                    <div class="quick-action-icon mb-3">
                                        <i class="fas fa-cow fa-3x text-success"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Register Animal</h6>
                                    <small class="text-muted">Add new animal to registry</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('milk-production.quick-entry') }}" class="card quick-action-card text-center text-decoration-none h-100">
                                <div class="card-body">
                                    <div class="quick-action-icon mb-3">
                                        <i class="fas fa-wine-bottle fa-3x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Record Milk</h6>
                                    <small class="text-muted">Enter daily milk production</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <a href="/health-records" class="card quick-action-card text-center text-decoration-none h-100">
                                <div class="card-body">
                                    <div class="quick-action-icon mb-3">
                                        <i class="fas fa-heartbeat fa-3x text-danger"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Health Check</h6>
                                    <small class="text-muted">Record health treatment</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('animals.index') }}" class="card quick-action-card text-center text-decoration-none h-100">
                                <div class="card-body">
                                    <div class="quick-action-icon mb-3">
                                        <i class="fas fa-list fa-3x text-primary"></i>
                                    </div>
                                    <h6 class="card-title mb-0">View Animals</h6>
                                    <small class="text-muted">Browse all animals</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card stat-card h-100">
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
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Lactating Cows</h6>
                            <h3 class="mb-0">{{ $stats['lactatingCows'] }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-wine-bottle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pregnant Cows</h6>
                            <h3 class="mb-0">{{ $stats['pregnantCows'] }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-baby fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Today's Milk (L)</h6>
                            <h3 class="mb-0">{{ number_format($stats['totalMilkToday'], 1) }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-weight fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats & Recent Animals -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-cow text-success me-2"></i>Animal Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Active Animals</span>
                            <span class="badge bg-success">{{ $allAnimals->where('is_active', true)->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Calves</span>
                            <span class="badge bg-info">{{ $allAnimals->where('status', 'calf')->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Heifers</span>
                            <span class="badge bg-primary">{{ $allAnimals->where('status', 'heifer')->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Dry Cows</span>
                            <span class="badge bg-warning">{{ $allAnimals->where('status', 'dry')->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Pregnant Cows</span>
                            <span class="badge bg-danger">{{ $allAnimals->where('status', 'pregnant')->count() }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Inactive/Sold</span>
                            <span class="badge bg-secondary">{{ $allAnimals->where('is_active', false)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-list text-success me-2"></i>Recent Animals
                    </h6>
                    <a href="{{ route('animals.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i>Add New
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Animal ID</th>
                                    <th>Name</th>
                                    <th>Breed</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAnimals as $animal)
                                <tr>
                                    <td>
                                        <strong>{{ $animal->animal_id }}</strong>
                                    </td>
                                    <td>{{ $animal->name ?? 'Unnamed' }}</td>
                                    <td>{{ $animal->breed }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'calf' => 'info',
                                                'heifer' => 'primary',
                                                'lactating' => 'success',
                                                'dry' => 'warning',
                                                'pregnant' => 'danger',
                                                'sold' => 'secondary',
                                                'dead' => 'dark'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$animal->status] ?? 'secondary' }}">
                                            {{ $animal->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('animals.show', $animal) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-md-8 mb-3">
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
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <span class="badge bg-danger me-2">Alert</span>
                                        {{ $record->animal->name ?? $record->animal->animal_id }}
                                    </h6>
                                    <small>{{ $record->date->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $record->diagnosis }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-stethoscope me-1"></i>{{ $record->veterinarian }}
                                </small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>No Health Issues</h5>
                            <p class="text-muted">All animals are healthy!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line text-info me-2"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Total Milk This Month</span>
                            <span class="badge bg-info">
                                @php
                                    $monthMilk = \App\Models\MilkProduction::whereMonth('date', now()->month)
                                        ->whereYear('date', now()->year)
                                        ->sum('total_yield');
                                @endphp
                                {{ number_format($monthMilk, 1) }} L
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Average Milk Per Day</span>
                            <span class="badge bg-primary">
                                @php
                                    $avgDaily = $stats['totalMilkToday'] > 0 ? $stats['totalMilkToday'] / max(1, $stats['lactatingCows']) : 0;
                                @endphp
                                {{ number_format($avgDaily, 1) }} L
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Animals Added This Month</span>
                            <span class="badge bg-success">
                                {{ \App\Models\Animal::whereMonth('created_at', now()->month)->count() }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Health Issues This Month</span>
                            <span class="badge bg-danger">
                                {{ \App\Models\HealthRecord::whereMonth('date', now()->month)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .quick-action-card {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-color: var(--farm-green);
    }
    
    .quick-action-card .card-body {
        padding: 1.5rem 1rem;
    }
    
    .quick-action-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(46, 125, 50, 0.1);
        transition: all 0.3s ease;
    }
    
    .quick-action-card:hover .quick-action-icon {
        background-color: rgba(46, 125, 50, 0.2);
        transform: scale(1.1);
    }
    
    .stat-card {
        border-left: 4px solid var(--farm-green);
    }
    
    .stat-card:hover {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
</style>
@endpush
@endsection