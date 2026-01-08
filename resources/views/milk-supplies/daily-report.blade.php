@extends('layouts.app')

@section('title', 'Daily Milk Supply Report - Dairy Farm Management')
@section('page-title', 'Daily Milk Supply Report')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-supplies.index') }}">Milk Supplies</a>
    </li>
    <li class="breadcrumb-item active">Daily Report</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-calendar-day me-2"></i>
                        Daily Milk Supply Report
                    </h5>
                    
                    <form action="{{ route('milk-supplies.daily-report') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">Select Date</label>
                            <input type="date" name="date" id="date" class="form-control" 
                                   value="{{ $date }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Supplied</h6>
                            <h3 class="mb-0">{{ number_format($totalLiters, 2) }} L</h3>
                        </div>
                        <div>
                            <i class="fas fa-wine-bottle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Waste</h6>
                            <h3 class="mb-0">{{ number_format($totalWaste, 2) }} L</h3>
                        </div>
                        <div>
                            <i class="fas fa-trash fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Revenue</h6>
                            <h3 class="mb-0">KSh {{ number_format($totalAmount, 2) }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Milk Supplies for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
            </h5>
        </div>
        <div class="card-body">
            @if($supplies->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>Quantity (L)</th>
                                <th>Waste (L)</th>
                                <th>Rate/Liter</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplies as $supply)
                            <tr>
                                <td>
                                    <a href="{{ route('suppliers.show', $supply->supplier_id) }}">
                                        {{ $supply->supplier->name }}
                                    </a>
                                </td>
                                <td>{{ number_format($supply->quantity_liters, 2) }}</td>
                                <td>{{ number_format($supply->waste_liters, 2) }}</td>
                                <td>KSh {{ number_format($supply->rate_per_liter, 2) }}</td>
                                <td>KSh {{ number_format($supply->total_amount, 2) }}</td>
                                <td>
                                    @if($supply->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($supply->status === 'recorded')
                                        <span class="badge bg-info">Recorded</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>{{ $supply->recorder->name ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th>TOTAL</th>
                                <th>{{ number_format($totalLiters, 2) }} L</th>
                                <th>{{ number_format($totalWaste, 2) }} L</th>
                                <th>-</th>
                                <th>KSh {{ number_format($totalAmount, 2) }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Print Button -->
                <div class="d-flex justify-content-end mt-3">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-wine-bottle fa-4x text-muted mb-3"></i>
                    <h4>No Milk Supplies for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h4>
                    <p class="text-muted">No milk supply records found for this date.</p>
                    <a href="{{ route('milk-supplies.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Record Milk Supply
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .sidebar, .top-navbar, .breadcrumb, .btn {
        display: none !important;
    }
    .main-wrapper {
        margin-left: 0 !important;
    }
    .card {
        border: 1px solid #000 !important;
    }
}
</style>
@endsection