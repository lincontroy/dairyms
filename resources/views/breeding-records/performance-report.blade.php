@extends('layouts.app')

@section('title', 'Breeding Performance Report - Dairy Farm Management')
@section('page-title', 'Breeding Performance Report')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('breeding-records.index') }}">Breeding Records</a>
    </li>
    <li class="breadcrumb-item active">Performance Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Year Selection -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('breeding-records.performance-report') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="year" class="form-label">Select Year</label>
                    <select class="form-select" id="year" name="year">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('breeding-records.performance-report', ['year' => now()->year]) }}" 
                       class="btn btn-outline-secondary">
                        Current Year
                    </a>
                    <a href="{{ route('breeding-records.performance-report', ['year' => now()->year - 1]) }}" 
                       class="btn btn-outline-secondary ms-2">
                        Previous Year
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-syringe fa-3x mb-3 opacity-50"></i>
                    <h3>{{ $stats['total_services'] }}</h3>
                    <h6>Total Services</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x mb-3 opacity-50"></i>
                    <h3>{{ $stats['confirmed_pregnant'] }}</h3>
                    <h6>Confirmed Pregnant</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-3x mb-3 opacity-50"></i>
                    <h3>{{ $stats['confirmed_not_pregnant'] }}</h3>
                    <h6>Not Pregnant</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-3x mb-3 opacity-50"></i>
                    <h3>{{ number_format($stats['conception_rate'], 1) }}%</h3>
                    <h6>Conception Rate</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-line me-2"></i>
                Monthly Conception Rate - {{ $year }}
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Services</th>
                            <th>Pregnant</th>
                            <th>Conception Rate</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyStats as $monthNum => $monthData)
                        @php
                            $monthName = DateTime::createFromFormat('!m', $monthNum)->format('F');
                            $performance = $monthData['rate'] >= 50 ? 'Excellent' : 
                                          ($monthData['rate'] >= 40 ? 'Good' : 
                                          ($monthData['rate'] >= 30 ? 'Fair' : 'Poor'));
                            $performanceColor = $monthData['rate'] >= 50 ? 'success' : 
                                              ($monthData['rate'] >= 40 ? 'info' : 
                                              ($monthData['rate'] >= 30 ? 'warning' : 'danger'));
                        @endphp
                        <tr>
                            <td><strong>{{ $monthName }}</strong></td>
                            <td>{{ $monthData['services'] }}</td>
                            <td>{{ $monthData['pregnant'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $performanceColor }}" 
                                             role="progressbar" 
                                             style="width: {{ min(100, $monthData['rate']) }}%"
                                             aria-valuenow="{{ $monthData['rate'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($monthData['rate'], 1) }}%
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $performanceColor }}">{{ $performance }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <td><strong>Year {{ $year }} Total</strong></td>
                            <td><strong>{{ $stats['total_services'] }}</strong></td>
                            <td><strong>{{ $stats['confirmed_pregnant'] }}</strong></td>
                            <td>
                                <strong>{{ number_format($stats['conception_rate'], 1) }}%</strong>
                            </td>
                            <td>
                                @php
                                    $yearPerformance = $stats['conception_rate'] >= 50 ? 'Excellent' : 
                                                     ($stats['conception_rate'] >= 40 ? 'Good' : 
                                                     ($stats['conception_rate'] >= 30 ? 'Fair' : 'Poor'));
                                    $yearColor = $stats['conception_rate'] >= 50 ? 'success' : 
                                                ($stats['conception_rate'] >= 40 ? 'info' : 
                                                ($stats['conception_rate'] >= 30 ? 'warning' : 'danger'));
                                @endphp
                                <span class="badge bg-{{ $yearColor }}">{{ $yearPerformance }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Breeding Method Performance -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Breeding Method Distribution
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $methods = $breedingRecords->groupBy('breeding_method')->map->count();
                        $totalMethods = $breedingRecords->count();
                    @endphp
                    
                    @if($totalMethods > 0)
                        <div class="list-group list-group-flush">
                            @foreach($methods as $method => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span>{{ $method }}</span>
                                <div>
                                    <span class="badge bg-{{ $method == 'AI' ? 'info' : ($method == 'Natural' ? 'success' : 'warning') }}">
                                        {{ $count }}
                                    </span>
                                    <small class="text-muted ms-2">
                                        {{ number_format(($count / $totalMethods) * 100, 1) }}%
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                            <h6>No Data Available</h6>
                            <p class="text-muted mb-0">No breeding records for {{ $year }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bullseye me-2"></i>
                        Performance Targets
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Target Conception Rate</span>
                            <div>
                                <span class="badge bg-success">50%+</span>
                                <span class="ms-2 {{ $stats['conception_rate'] >= 50 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-{{ $stats['conception_rate'] >= 50 ? 'check' : 'times' }}"></i>
                                </span>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Services per Pregnancy</span>
                            <div>
                                <span class="badge bg-success">≤ 2.0</span>
                                @php
                                    $servicesPerPregnancy = $stats['confirmed_pregnant'] > 0 ? 
                                        $stats['total_services'] / $stats['confirmed_pregnant'] : 0;
                                @endphp
                                <span class="ms-2 {{ $servicesPerPregnancy <= 2.0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($servicesPerPregnancy, 1) }}
                                    <i class="fas fa-{{ $servicesPerPregnancy <= 2.0 ? 'check' : 'times' }} ms-1"></i>
                                </span>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Pregnancy Check Rate</span>
                            <div>
                                <span class="badge bg-success">90%+</span>
                                @php
                                    $checkRate = $stats['total_services'] > 0 ? 
                                        (($stats['confirmed_pregnant'] + $stats['confirmed_not_pregnant']) / $stats['total_services']) * 100 : 0;
                                @endphp
                                <span class="ms-2 {{ $checkRate >= 90 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($checkRate, 1) }}%
                                    <i class="fas fa-{{ $checkRate >= 90 ? 'check' : 'times' }} ms-1"></i>
                                </span>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span>Calving Success Rate</span>
                            <div>
                                <span class="badge bg-success">85%+</span>
                                @php
                                    $calvingRate = $stats['confirmed_pregnant'] > 0 ? 
                                        ($stats['successful_calvings'] / $stats['confirmed_pregnant']) * 100 : 0;
                                @endphp
                                <span class="ms-2 {{ $calvingRate >= 85 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($calvingRate, 1) }}%
                                    <i class="fas fa-{{ $calvingRate >= 85 ? 'check' : 'times' }} ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export & Reports -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-download me-2"></i>
                Export Reports
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                            <h5>Excel Report</h5>
                            <p class="text-muted">Download detailed performance data</p>
                            <a href="#" class="btn btn-outline-success">
                                <i class="fas fa-download me-2"></i>Download Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                            <h5>PDF Report</h5>
                            <p class="text-muted">Generate printable PDF report</p>
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-3x text-primary mb-3"></i>
                            <h5>Charts & Graphs</h5>
                            <p class="text-muted">View visual performance analysis</p>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-chart-line me-2"></i>View Charts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection