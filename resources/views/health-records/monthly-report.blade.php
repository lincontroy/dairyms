@extends('layouts.app')

@section('title', 'Monthly Health Report - Dairy Farm Management')
@section('page-title', 'Monthly Health Report')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('health-records.index') }}">Health Records</a>
    </li>
    <li class="breadcrumb-item active">Monthly Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Month Selection -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('health-records.monthly-report') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="month" class="form-label">Select Month</label>
                    <input type="month" 
                           class="form-control" 
                           id="month" 
                           name="month" 
                           value="{{ $month }}"
                           max="{{ now()->format('Y-m') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('health-records.monthly-report', ['month' => now()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary">
                        Current Month
                    </a>
                    <a href="{{ route('health-records.monthly-report', ['month' => now()->subMonth()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary ms-2">
                        Previous Month
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h6 class="text-white-50">Month</h6>
                            <h3 class="mb-0">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-white-50">Total Cases</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-white-50">Recovered</h6>
                            <h3 class="mb-0">{{ $stats['recovered'] }}</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-white-50">Under Treatment</h6>
                            <h3 class="mb-0">{{ $stats['under_treatment'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Outcome Distribution -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Health Outcomes Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span>
                                        <span class="badge bg-success me-2">●</span>
                                        Recovered
                                    </span>
                                    <div>
                                        <span class="badge bg-success">{{ $stats['recovered'] }}</span>
                                        @if($stats['total'] > 0)
                                            <small class="text-muted ms-2">
                                                {{ number_format(($stats['recovered'] / $stats['total']) * 100, 1) }}%
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span>
                                        <span class="badge bg-warning me-2">●</span>
                                        Under Treatment
                                    </span>
                                    <div>
                                        <span class="badge bg-warning">{{ $stats['under_treatment'] }}</span>
                                        @if($stats['total'] > 0)
                                            <small class="text-muted ms-2">
                                                {{ number_format(($stats['under_treatment'] / $stats['total']) * 100, 1) }}%
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span>
                                        <span class="badge bg-danger me-2">●</span>
                                        Not Responding
                                    </span>
                                    <div>
                                        <span class="badge bg-danger">{{ $stats['not_responding'] ?? 0 }}</span>
                                        @if($stats['total'] > 0)
                                            <small class="text-muted ms-2">
                                                {{ number_format((($stats['not_responding'] ?? 0) / $stats['total']) * 100, 1) }}%
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                    <span>
                                        <span class="badge bg-dark me-2">●</span>
                                        Died
                                    </span>
                                    <div>
                                        <span class="badge bg-dark">{{ $stats['died'] }}</span>
                                        @if($stats['total'] > 0)
                                            <small class="text-muted ms-2">
                                                {{ number_format(($stats['died'] / $stats['total']) * 100, 1) }}%
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center py-4">
                                @if($stats['total'] > 0)
                                    <div class="display-4 text-primary mb-2">
                                        @php
                                            $recoveryRate = ($stats['recovered'] / $stats['total']) * 100;
                                        @endphp
                                        {{ number_format($recoveryRate, 1) }}%
                                    </div>
                                    <h6>Recovery Rate</h6>
                                    <small class="text-muted">
                                        {{ $stats['recovered'] }} out of {{ $stats['total'] }} cases
                                    </small>
                                @else
                                    <i class="fas fa-chart-pie fa-4x text-muted mb-3"></i>
                                    <h6>No Data Available</h6>
                                    <p class="text-muted mb-0">No health records for this month</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        Common Diagnoses
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        // Group by diagnosis
                        $diagnoses = [];
                        foreach($healthRecords as $record) {
                            $diagnosis = $record->diagnosis;
                            if (!isset($diagnoses[$diagnosis])) {
                                $diagnoses[$diagnosis] = 0;
                            }
                            $diagnoses[$diagnosis]++;
                        }
                        arsort($diagnoses);
                    @endphp
                    
                    @if(count($diagnoses) > 0)
                        <div class="list-group list-group-flush">
                            @foreach(array_slice($diagnoses, 0, 5) as $diagnosis => $count)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-truncate">{{ $diagnosis }}</span>
                                <span class="badge bg-info">{{ $count }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                            <h6>No Diagnoses</h6>
                            <p class="text-muted mb-0">No health records found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Records -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Health Records for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
            </h5>
            <div>
                <span class="badge bg-light text-dark">{{ $healthRecords->count() }} records</span>
            </div>
        </div>
        <div class="card-body">
            @if($healthRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Animal</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Drug</th>
                                <th>Withdrawal</th>
                                <th>Outcome</th>
                                <th>Veterinarian</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($healthRecords as $record)
                            <tr>
                                <td>{{ $record->date->format('M d') }}</td>
                                <td>
                                    <a href="{{ route('animals.show', $record->animal_id) }}">
                                        {{ $record->animal->animal_id }}
                                        @if($record->animal->name)
                                            <br><small>{{ $record->animal->name }}</small>
                                        @endif
                                    </a>
                                </td>
                                <td>{{ Str::limit($record->diagnosis, 25) }}</td>
                                <td>{{ Str::limit($record->treatment, 30) }}</td>
                                <td>{{ $record->drug_name ?? 'N/A' }}</td>
                                <td>
                                    @if($record->milk_withdrawal_days || $record->meat_withdrawal_days)
                                        @if($record->milk_withdrawal_days)
                                            <span class="badge bg-warning">M:{{ $record->milk_withdrawal_days }}d</span>
                                        @endif
                                        @if($record->meat_withdrawal_days)
                                            <span class="badge bg-danger">F:{{ $record->meat_withdrawal_days }}d</span>
                                        @endif
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->outcome === 'Recovered')
                                        <span class="badge bg-success">Recovered</span>
                                    @elseif($record->outcome === 'Under Treatment')
                                        <span class="badge bg-warning">Under Treatment</span>
                                    @elseif($record->outcome === 'Not Responding')
                                        <span class="badge bg-danger">Not Responding</span>
                                    @elseif($record->outcome === 'Died')
                                        <span class="badge bg-dark">Died</span>
                                    @endif
                                </td>
                                <td>{{ $record->veterinarian ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('health-records.show', $record) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('health-records.edit', $record) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                    <h4>No Health Records</h4>
                    <p class="text-muted">No health records found for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}.</p>
                    <a href="{{ route('health-records.create') }}" class="btn btn-danger">
                        <i class="fas fa-plus me-2"></i>Add Health Record
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Export Options -->
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                    <h5>Export to Excel</h5>
                    <p class="text-muted">Download health records as spreadsheet</p>
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
                    <h5>Generate PDF Report</h5>
                    <p class="text-muted">Create professional PDF report</p>
                    <a href="#" class="btn btn-outline-danger">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                    <h5>Trend Analysis</h5>
                    <p class="text-muted">View health trends over time</p>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar me-2"></i>View Trends
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
    
    .display-4 {
        font-size: 3.5rem;
        font-weight: 300;
        line-height: 1.2;
    }
    
    .badge.bg-light {
        color: #212529 !important;
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update the URL when month changes
        const monthInput = document.getElementById('month');
        if (monthInput) {
            monthInput.addEventListener('change', function() {
                // You could auto-submit or update the page
                // this.form.submit();
            });
        }
        
        // Initialize tooltips if needed
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush