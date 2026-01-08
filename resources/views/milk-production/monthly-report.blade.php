@extends('layouts.app')

@section('title', 'Monthly Milk Report - Dairy Farm Management')
@section('page-title', 'Monthly Milk Report')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-production.index') }}">Milk Production</a>
    </li>
    <li class="breadcrumb-item active">Monthly Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Month Selection -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('milk-production.monthly-report') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-lg-4">
                    <label for="month" class="form-label">Select Month</label>
                    <input type="month" 
                           class="form-control" 
                           id="month" 
                           name="month" 
                           value="{{ $month }}"
                           max="{{ now()->format('Y-m') }}">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('milk-production.monthly-report', ['month' => now()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary w-100">
                        Current
                    </a>
                </div>
                <div class="col-12 col-md-6 col-lg-4 text-md-end mt-2 mt-md-0">
                    <a href="{{ route('milk-production.monthly-report', ['month' => now()->subMonth()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary">
                        Previous Month
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body p-3">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <h6 class="text-white-50 mb-1">Month</h6>
                            <h4 class="mb-0">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h4>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <h6 class="text-white-50 mb-1">Total Milk</h6>
                            <h4 class="mb-0">{{ number_format($monthTotal, 2) }} L</h4>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <h6 class="text-white-50 mb-1">Daily Average</h6>
                            <h4 class="mb-0">
                                @php
                                    $daysInMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth;
                                    $avgDaily = $daysInMonth > 0 ? $monthTotal / $daysInMonth : 0;
                                @endphp
                                {{ number_format($avgDaily, 2) }} L
                            </h4>
                        </div>
                        <div class="col-6 col-md-3">
                            <h6 class="text-white-50 mb-1">Records</h6>
                            <h4 class="mb-0">{{ $milkProductions->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="card-title mb-2 mb-md-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Daily Milk Production
            </h5>
            <div class="text-muted small">
                Showing {{ $dailyTotals->count() }} days
            </div>
        </div>
        <div class="card-body p-0 p-md-3">
            @if($milkProductions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-center">Records</th>
                                <th class="text-end">Total Milk (L)</th>
                                <th class="text-end">Avg/Animal</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyTotals as $date => $total)
                                @php
                                    $records = $milkProductions[$date] ?? collect();
                                    $animalCount = $records->count();
                                    $avgPerAnimal = $animalCount > 0 ? $total / $animalCount : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($date)->format('l') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $animalCount }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($total, 2) }} L</strong>
                                    </td>
                                    <td class="text-end">{{ number_format($avgPerAnimal, 2) }} L</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#dayModal{{ str_replace('-', '', $date) }}"
                                                    title="View Details">
                                                <i class="fas fa-list"></i>
                                                <span class="d-none d-md-inline ms-1">Details</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        @if($dailyTotals->count() > 0)
                        <tfoot>
                            <tr class="table-secondary">
                                <th>Total</th>
                                <th class="text-center">{{ $milkProductions->count() }}</th>
                                <th class="text-end">{{ number_format($monthTotal, 2) }} L</th>
                                <th class="text-end">
                                    @php
                                        $totalAnimals = $milkProductions->count();
                                        $avgOverall = $totalAnimals > 0 ? $monthTotal / $totalAnimals : 0;
                                    @endphp
                                    {{ number_format($avgOverall, 2) }} L
                                </th>
                                <th class="text-center"></th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                
                <!-- Modals for daily details -->
                @foreach($dailyTotals as $date => $total)
                    @php
                        $records = $milkProductions[$date] ?? collect();
                    @endphp
                    <div class="modal fade" id="dayModal{{ str_replace('-', '', $date) }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-calendar-day me-2"></i>
                                        Milk Production for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Animal ID</th>
                                                    <th class="text-end">Morning</th>
                                                    <th class="text-end">Evening</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($records as $record)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $record->animal->animal_id }}</strong>
                                                            @if($record->animal->name)
                                                                <div class="text-muted small">{{ $record->animal->name }}</div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-end">{{ number_format($record->morning_yield, 2) }} L</td>
                                                    <td class="text-end">{{ number_format($record->evening_yield, 2) }} L</td>
                                                    <td class="text-end fw-medium">{{ number_format($record->total_yield, 2) }} L</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-secondary">
                                                <tr>
                                                    <th>Total</th>
                                                    <th class="text-end">{{ number_format($records->sum('morning_yield'), 2) }} L</th>
                                                    <th class="text-end">{{ number_format($records->sum('evening_yield'), 2) }} L</th>
                                                    <th class="text-end">{{ number_format($total, 2) }} L</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fas fa-wine-bottle fa-4x text-muted mb-3"></i>
                    <h4>No Milk Production Records</h4>
                    <p class="text-muted mb-4">No milk production records found for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}.</p>
                    <a href="{{ route('milk-production.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add Milk Record
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Export Options -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-download me-2"></i>
                Export Options
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="card h-100 border">
                        <div class="card-body text-center p-3">
                            <div class="mb-3">
                                <i class="fas fa-file-excel fa-3x text-success"></i>
                            </div>
                            <h6 class="mb-2">Excel Export</h6>
                            <p class="text-muted small mb-3">Download as Excel spreadsheet</p>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="card h-100 border">
                        <div class="card-body text-center p-3">
                            <div class="mb-3">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                            </div>
                            <h6 class="mb-2">PDF Report</h6>
                            <p class="text-muted small mb-3">Generate PDF report</p>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-4">
                    <div class="card h-100 border">
                        <div class="card-body text-center p-3">
                            <div class="mb-3">
                                <i class="fas fa-chart-line fa-3x text-primary"></i>
                            </div>
                            <h6 class="mb-2">Charts</h6>
                            <p class="text-muted small mb-3">View graphical analysis</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-chart-bar me-1"></i>View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Responsive table styles */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table th,
    .table td {
        white-space: nowrap;
        padding: 0.75rem 0.5rem;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.875rem;
        }
        
        h4 {
            font-size: 1.25rem;
        }
        
        h5 {
            font-size: 1rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .card {
            margin-bottom: 0.75rem;
        }
        
        .card-header {
            padding: 0.75rem;
        }
        
        .modal-title {
            font-size: 0.9rem;
        }
        
        /* Force table cells to wrap on very small screens */
        .table-responsive .table td {
            white-space: normal;
            min-width: 80px;
        }
    }
    
    /* Print styles */
    @media print {
        .btn, 
        .modal, 
        .modal-backdrop,
        .breadcrumb,
        .sidebar,
        .top-navbar {
            display: none !important;
        }
        
        .container-fluid {
            width: 100%;
            padding: 0;
        }
        
        .card {
            border: 1px solid #000;
            break-inside: avoid;
        }
        
        .table {
            font-size: 11px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when month changes (optional)
    const monthInput = document.getElementById('month');
    if (monthInput) {
        monthInput.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    // Handle modal cleanup
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        });
    });
});
</script>
@endpush