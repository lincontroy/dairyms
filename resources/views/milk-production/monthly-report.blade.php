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
                    <a href="{{ route('milk-production.monthly-report', ['month' => now()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary">
                        Current Month
                    </a>
                    <a href="{{ route('milk-production.monthly-report', ['month' => now()->subMonth()->format('Y-m')]) }}" 
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
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h6 class="text-white-50">Month</h6>
                            <h3 class="mb-0">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-white-50">Total Milk</h6>
                            <h3 class="mb-0">{{ number_format($monthTotal, 2) }} L</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-white-50">Daily Average</h6>
                            <h3 class="mb-0">
                                @php
                                    $daysInMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->daysInMonth;
                                    $avgDaily = $daysInMonth > 0 ? $monthTotal / $daysInMonth : 0;
                                @endphp
                                {{ number_format($avgDaily, 2) }} L
                            </h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-white-50">Records Count</h6>
                            <h3 class="mb-0">{{ $milkProductions->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Daily Milk Production
            </h5>
        </div>
        <div class="card-body">
            @if($milkProductions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Records</th>
                                <th>Total Milk (L)</th>
                                <th>Avg Per Animal</th>
                                <th>Actions</th>
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
                                    <td>{{ \Carbon\Carbon::parse($date)->format('F d, Y (l)') }}</td>
                                    <td>{{ $animalCount }} animals</td>
                                    <td>
                                        <strong>{{ number_format($total, 2) }} L</strong>
                                    </td>
                                    <td>{{ number_format($avgPerAnimal, 2) }} L</td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#dayModal{{ str_replace('-', '', $date) }}">
                                            <i class="fas fa-list"></i> Details
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Modal for daily details -->
                                <div class="modal fade" id="dayModal{{ str_replace('-', '', $date) }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Milk Production for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Animal</th>
                                                                <th>Morning</th>
                                                                <th>Evening</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($records as $record)
                                                            <tr>
                                                                <td>
                                                                    {{ $record->animal->animal_id }}
                                                                    @if($record->animal->name)
                                                                        <br><small>{{ $record->animal->name }}</small>
                                                                    @endif
                                                                </td>
                                                                <td>{{ number_format($record->morning_yield, 2) }} L</td>
                                                                <td>{{ number_format($record->evening_yield, 2) }} L</td>
                                                                <td>{{ number_format($record->total_yield, 2) }} L</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="table-secondary">
                                                                <th>Total</th>
                                                                <th>{{ number_format($records->sum('morning_yield'), 2) }} L</th>
                                                                <th>{{ number_format($records->sum('evening_yield'), 2) }} L</th>
                                                                <th>{{ number_format($total, 2) }} L</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-wine-bottle fa-4x text-muted mb-3"></i>
                    <h4>No Milk Production Records</h4>
                    <p class="text-muted">No milk production records found for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}.</p>
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
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                            <h5>Excel Export</h5>
                            <p class="text-muted">Download as Excel spreadsheet</p>
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
                            <p class="text-muted">Generate PDF report</p>
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
                            <h5>Charts</h5>
                            <p class="text-muted">View graphical analysis</p>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-chart-bar me-2"></i>View Charts
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
    .modal-body table {
        margin-bottom: 0;
    }
    
    .modal-body tbody tr:last-child {
        border-bottom: none;
    }
</style>
@endpush