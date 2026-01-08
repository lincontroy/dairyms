@extends('layouts.app')

@section('title', 'Milk Supplies - Dairy Farm Management')
@section('page-title', 'Milk Supplies')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Milk Supplies</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Supply</h6>
                            <h3 class="mb-0">{{ number_format($todayTotal, 2) }} L</h3>
                        </div>
                        <div>
                            <i class="fas fa-truck-loading fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Waste</h6>
                            <h3 class="mb-0">{{ number_format($todayWaste, 2) }} L</h3>
                        </div>
                        <div>
                            <i class="fas fa-trash fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">This Month</h6>
                            <h3 class="mb-0">{{ number_format($monthTotal, 2) }} L</h3>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Revenue</h6>
                            <h3 class="mb-0">
                                KSh {{ number_format(\App\Models\MilkSupply::sum('total_amount'), 2) }}
                            </h3>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Milk Supply Management</h5>
                        <div>
                            <a href="{{ route('milk-supplies.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Record Supply
                            </a>
                            <a href="{{ route('milk-supplies.daily-report') }}" class="btn btn-primary ms-2">
                                <i class="fas fa-file-alt me-2"></i>Daily Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('milk-supplies.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" 
                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" id="date" class="form-control" 
                           value="{{ request('date') }}">
                </div>
                
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="recorded" {{ request('status') == 'recorded' ? 'selected' : '' }}>Recorded</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('milk-supplies.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Milk Supplies Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Milk Supply Records
            </h5>
        </div>
        <div class="card-body">
            @if($milkSupplies->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Quantity (L)</th>
                                <th>Waste (L)</th>
                                <th>Rate/Liter</th>
                                <th>Total Amount</th>
                                <th>Recorded By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                       <!-- In the table body -->
@foreach($milkSupplies as $supply)
<tr>
    <td>{{ $supply->date->format('M d, Y') }}</td>
    <td>
        <a href="{{ route('suppliers.show', $supply->supplier_id) }}">
            <strong>{{ $supply->supplier->name }}</strong>
        </a>
    </td>
    <td>{{ number_format($supply->quantity_liters, 2) }}</td>
    <td>
        @if($supply->waste_liters > 0)
            <span class="badge bg-warning">{{ number_format($supply->waste_liters, 2) }}</span>
        @else
            <span class="text-muted">0.00</span>
        @endif
    </td>
    <td>KSh {{ number_format($supply->rate_per_liter, 2) }}</td>
    <td>
        <strong>KSh {{ number_format($supply->total_amount, 2) }}</strong>
    </td>
    <td>{{ $supply->recorder->name ?? 'N/A' }}</td>
    <td>
        <!-- Milk Supply Status -->
        @if($supply->status === 'approved')
            <span class="badge bg-success">Approved</span>
        @elseif($supply->status === 'recorded')
            <span class="badge bg-info">Recorded</span>
        @else
            <span class="badge bg-danger">Cancelled</span>
        @endif
        
        <!-- Payment Status -->
        <br>
        @if($supply->payment)
            @if($supply->payment->status === 'pending')
                <span class="badge bg-warning">Payment Pending</span>
            @elseif($supply->payment->status === 'approved')
                <span class="badge bg-success">Paid</span>
            @else
                <span class="badge bg-danger">Payment Rejected</span>
            @endif
        @else
            <span class="badge bg-secondary">No Payment</span>
        @endif
    </td>
    <td>
        <div class="btn-group btn-group-sm">
            <a href="{{ route('milk-supplies.show', $supply) }}" 
               class="btn btn-outline-primary">
                <i class="fas fa-eye"></i>
            </a>
            @if($supply->isRecorded())
                <a href="{{ route('milk-supplies.edit', $supply) }}" 
                   class="btn btn-outline-warning">
                    <i class="fas fa-edit"></i>
                </a>
                @if(auth()->user()->canApprovePayments())
                    <form action="{{ route('milk-supplies.approve', $supply) }}" 
                          method="POST" class="d-inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Approve this milk supply?')"
                                class="btn btn-outline-success">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </td>
</tr>
@endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $milkSupplies->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-truck-loading fa-4x text-muted mb-3"></i>
                    <h4>No Milk Supply Records</h4>
                    <p class="text-muted">Start recording milk supplies to see data here.</p>
                    <a href="{{ route('milk-supplies.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Record First Supply
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection