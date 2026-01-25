@extends('layouts.app')

@section('title', 'Calf Statistics - Dairy Farm')
@section('page-title', 'Calf Statistics')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('calves.index') }}">Calves</a></li>
<li class="breadcrumb-item active">Statistics</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar text-primary me-2"></i>
                                Calf Statistics & Analytics
                            </h5>
                            <p class="text-muted mb-0">Comprehensive overview of calf population and performance</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('calves.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Calves
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Calves</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['total_calves'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-baby fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Alive Calves</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['alive_calves'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Born This Month</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['calves_born_this_month'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-danger border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                Special Care</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['calves_requiring_special_care'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-medkit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="row mb-4">
        <!-- Status Distribution -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Status Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                    <div class="mt-3">
                        <table class="table table-sm">
                            <tr>
                                <td><span class="badge bg-success">●</span> Alive</td>
                                <td class="text-end">{{ $stats['alive_calves'] }}</td>
                                <td class="text-end">{{ $stats['total_calves'] > 0 ? round(($stats['alive_calves'] / $stats['total_calves']) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">●</span> Dead</td>
                                <td class="text-end">{{ $stats['dead_calves'] }}</td>
                                <td class="text-end">{{ $stats['total_calves'] > 0 ? round(($stats['dead_calves'] / $stats['total_calves']) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning">●</span> Sold</td>
                                <td class="text-end">{{ $stats['sold_calves'] }}</td>
                                <td class="text-end">{{ $stats['total_calves'] > 0 ? round(($stats['sold_calves'] / $stats['total_calves']) * 100, 1) : 0 }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-venus-mars me-2"></i>
                        Gender Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="genderChart" height="200"></canvas>
                    </div>
                    <div class="mt-3">
                        <table class="table table-sm">
                            <tr>
                                <td><span class="badge bg-info">●</span> Male</td>
                                <td class="text-end">{{ $stats['male_calves'] }}</td>
                                <td class="text-end">{{ $stats['total_calves'] > 0 ? round(($stats['male_calves'] / $stats['total_calves']) * 100, 1) : 0 }}%</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-pink">●</span> Female</td>
                                <td class="text-end">{{ $stats['female_calves'] }}</td>
                                <td class="text-end">{{ $stats['total_calves'] > 0 ? round(($stats['female_calves'] / $stats['total_calves']) * 100, 1) : 0 }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-baby-carriage me-2"></i>
                        Age Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @foreach($ageDistribution as $ageGroup => $count)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ $ageGroup }}</span>
                                <span>{{ $count }} calves</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar 
                                    @if($loop->index == 0) bg-success
                                    @elseif($loop->index == 1) bg-info
                                    @elseif($loop->index == 2) bg-warning
                                    @else bg-secondary @endif" 
                                    role="progressbar" 
                                    style="width: {{ $stats['total_calves'] > 0 ? ($count / $stats['total_calves'] * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <table class="table table-sm">
                        @foreach($ageDistribution as $ageGroup => $count)
                        <tr>
                            <td>{{ $ageGroup }}</td>
                            <td class="text-end">{{ $count }}</td>
                            <td class="text-end">{{ $stats['total_calves'] > 0 ? round(($count / $stats['total_calves']) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Performance Metrics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="metric-value">
                                {{ $stats['weaned_calves'] }}
                            </div>
                            <div class="metric-label">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Weaned Calves
                            </div>
                            <small class="text-muted">
                                {{ $stats['total_calves'] > 0 ? round(($stats['weaned_calves'] / $stats['total_calves']) * 100, 1) : 0 }}% success rate
                            </small>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="metric-value">
                                {{ $stats['calves_needing_weaning'] }}
                            </div>
                            <div class="metric-label">
                                <i class="fas fa-clock text-warning me-1"></i>
                                Due for Weaning
                            </div>
                            <small class="text-muted">
                                Require weaning attention
                            </small>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="metric-value">
                                {{ $stats['calves_born_last_30_days'] }}
                            </div>
                            <div class="metric-label">
                                <i class="fas fa-calendar-alt text-info me-1"></i>
                                Born Last 30 Days
                            </div>
                            <small class="text-muted">
                                Recent births
                            </small>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="metric-value">
                                @if($stats['dead_calves'] > 0)
                                {{ $stats['total_calves'] > 0 ? round(($stats['dead_calves'] / $stats['total_calves']) * 100, 1) : 0 }}%
                                @else
                                0%
                                @endif
                            </div>
                            <div class="metric-label">
                                <i class="fas fa-skull-crossbones text-danger me-1"></i>
                                Mortality Rate
                            </div>
                            <small class="text-muted">
                                Percentage of dead calves
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Recent Calves (Last 10)
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentCalves = \App\Models\Calf::latest()->take(10)->get();
                    @endphp
                    
                    @if($recentCalves->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Calf ID</th>
                                    <th>Name</th>
                                    <th>Dam</th>
                                    <th>Date of Birth</th>
                                    <th>Age</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCalves as $calf)
                                <tr>
                                    <td>
                                        <a href="{{ route('calves.show', $calf) }}" class="text-decoration-none">
                                            {{ $calf->calf_id }}
                                        </a>
                                    </td>
                                    <td>{{ $calf->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($calf->dam)
                                        {{ $calf->dam->name ?? $calf->dam->ear_tag }}
                                        @else
                                        Unknown
                                        @endif
                                    </td>
                                    <td>{{ $calf->date_of_birth->format('M d, Y') }}</td>
                                    <td>{{ $calf->age_in_days }} days</td>
                                    <td>
                                        <span class="badge {{ $calf->status == 'alive' ? 'bg-success' : ($calf->status == 'dead' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($calf->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-baby fa-3x mb-3"></i>
                        <p>No calves recorded yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge.bg-pink {
        background-color: #e83e8c;
        color: white;
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2E7D32;
        line-height: 1;
    }
    
    .metric-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .chart-container {
        position: relative;
        height: 200px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Alive', 'Dead', 'Sold'],
            datasets: [{
                data: [
                    {{ $stats['alive_calves'] }},
                    {{ $stats['dead_calves'] }},
                    {{ $stats['sold_calves'] }}
                ],
                backgroundColor: [
                    '#198754',
                    '#dc3545',
                    '#ffc107'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    const genderChart = new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [
                    {{ $stats['male_calves'] }},
                    {{ $stats['female_calves'] }}
                ],
                backgroundColor: [
                    '#0dcaf0',
                    '#e83e8c'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush