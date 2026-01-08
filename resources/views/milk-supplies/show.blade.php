@extends('layouts.app')

@section('title', 'Milk Supply Details - Dairy Farm Management')
@section('page-title', 'Milk Supply Details')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-supplies.index') }}">Milk Supplies</a>
    </li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Milk Supply Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-wine-bottle me-2"></i>
                        Milk Supply Details
                    </h5>
                    <div>
                        @if($milkSupply->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($milkSupply->status === 'recorded')
                            <span class="badge bg-info">Recorded</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Supplier</label>
                                <h5 class="mb-0">
                                    <a href="{{ route('suppliers.show', $milkSupply->supplier_id) }}" class="text-decoration-none">
                                        <i class="fas fa-truck me-2 text-primary"></i>
                                        {{ $milkSupply->supplier->name }}
                                    </a>
                                </h5>
                                @if($milkSupply->supplier->company_name)
                                    <small class="text-muted">{{ $milkSupply->supplier->company_name }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Supply Date</label>
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar me-2 text-success"></i>
                                    {{ $milkSupply->date->format('F d, Y') }}
                                </h5>
                                <small class="text-muted">{{ $milkSupply->date->format('l') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-success mb-3">
                                <div class="card-body text-center">
                                    <label class="text-muted small mb-1">Quantity Supplied</label>
                                    <h3 class="text-success mb-0">{{ number_format($milkSupply->quantity_liters, 2) }} L</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning mb-3">
                                <div class="card-body text-center">
                                    <label class="text-muted small mb-1">Waste</label>
                                    <h3 class="text-warning mb-0">{{ number_format($milkSupply->waste_liters, 2) }} L</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info mb-3">
                                <div class="card-body text-center">
                                    <label class="text-muted small mb-1">Net Quantity</label>
                                    <h3 class="text-info mb-0">{{ number_format($milkSupply->net_quantity, 2) }} L</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Rate per Liter</label>
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">KSh {{ number_format($milkSupply->rate_per_liter, 2) }}</h4>
                                    <small class="text-muted ms-2">per liter</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Total Amount</label>
                                <h3 class="text-success mb-0">KSh {{ number_format($milkSupply->total_amount, 2) }}</h3>
                                <small class="text-muted">Quantity × Rate</small>
                            </div>
                        </div>
                    </div>

                    @if($milkSupply->notes)
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Notes</label>
                        <div class="card">
                            <div class="card-body">
                                {{ $milkSupply->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Audit Trail
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Recorded By</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle me-2 text-primary"></i>
                                    <div>
                                        <strong>{{ $milkSupply->recorder->name ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $milkSupply->created_at->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($milkSupply->isApproved())
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Approved By</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    <div>
                                        <strong>{{ $milkSupply->approver->name ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $milkSupply->approved_at->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('milk-supplies.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                        
                        @if($milkSupply->isRecorded())
                            @can('update', $milkSupply)
                            <a href="{{ route('milk-supplies.edit', $milkSupply) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit me-2"></i>Edit Record
                            </a>
                            @endcan
                            
                            @can('approvePayments')
                            <form action="{{ route('milk-supplies.approve', $milkSupply) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Approve this milk supply record?')"
                                        class="btn btn-outline-success">
                                    <i class="fas fa-check me-2"></i>Approve
                                </button>
                            </form>
                            @endcan
                        @endif
                        
                        @if($milkSupply->isRecorded())
                            @can('delete', $milkSupply)
                            <form action="{{ route('milk-supplies.destroy', $milkSupply) }}" method="POST" class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this milk supply record?')"
                                        class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Delete Record
                                </button>
                            </form>
                            @endcan
                        @endif
                        
                        <!-- Create Payment Button -->
                        @if($milkSupply->isApproved() && !$milkSupply->payment)
                            <a href="{{ route('payments.create', ['milk_supply_id' => $milkSupply->id]) }}" 
                               class="btn btn-success">
                                <i class="fas fa-money-bill-wave me-2"></i>Create Payment
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Supplier Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-truck me-2"></i>
                        Supplier Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ $milkSupply->supplier->name }}</strong>
                        @if($milkSupply->supplier->company_name)
                            <div class="text-muted small">{{ $milkSupply->supplier->company_name }}</div>
                        @endif
                    </div>
                    
                    @if($milkSupply->supplier->contact_person)
                    <div class="mb-2">
                        <i class="fas fa-user me-2 text-muted"></i>
                        <span class="small">{{ $milkSupply->supplier->contact_person }}</span>
                    </div>
                    @endif
                    
                    @if($milkSupply->supplier->phone)
                    <div class="mb-2">
                        <i class="fas fa-phone me-2 text-muted"></i>
                        <span class="small">{{ $milkSupply->supplier->phone }}</span>
                    </div>
                    @endif
                    
                    @if($milkSupply->supplier->email)
                    <div class="mb-2">
                        <i class="fas fa-envelope me-2 text-muted"></i>
                        <span class="small">{{ $milkSupply->supplier->email }}</span>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('suppliers.show', $milkSupply->supplier_id) }}" 
                           class="btn btn-sm btn-outline-primary w-100">
                            View Supplier Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Status Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Current Status</label>
                        <div>
                            @if($milkSupply->status === 'approved')
                                <span class="badge bg-success p-2">
                                    <i class="fas fa-check-circle me-1"></i> Approved
                                </span>
                                @if($milkSupply->payment)
                                    <div class="mt-2">
                                        <label class="text-muted small mb-1">Payment Status</label>
                                        <div>
                                            <span class="badge bg-success">Paid</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <label class="text-muted small mb-1">Payment Status</label>
                                        <div>
                                            <span class="badge bg-warning">Pending Payment</span>
                                        </div>
                                    </div>
                                @endif
                            @elseif($milkSupply->status === 'recorded')
                                <span class="badge bg-info p-2">
                                    <i class="fas fa-clock me-1"></i> Recorded
                                </span>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Waiting for approval
                                    </small>
                                </div>
                            @else
                                <span class="badge bg-danger p-2">
                                    <i class="fas fa-times-circle me-1"></i> Cancelled
                                </span>
                            @endif
                        </div>
                    </div>
                    
                 <!-- Add this in the sidebar or main content -->
@if($milkSupply->payment)
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-money-bill-wave me-2"></i>
            Auto-Created Payment
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-muted small mb-1">Payment Status</label>
                    <div>
                        @if($milkSupply->payment->status === 'pending')
                            <span class="badge bg-warning p-2">
                                <i class="fas fa-clock me-1"></i> Pending Approval
                            </span>
                        @elseif($milkSupply->payment->status === 'approved')
                            <span class="badge bg-success p-2">
                                <i class="fas fa-check-circle me-1"></i> Approved
                            </span>
                        @else
                            <span class="badge bg-danger p-2">
                                <i class="fas fa-times-circle me-1"></i> Rejected
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-muted small mb-1">Amount</label>
                    <h4 class="mb-0">KSh {{ number_format($milkSupply->payment->amount, 2) }}</h4>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="text-muted small mb-1">Reference Number</label>
            <div>{{ $milkSupply->payment->reference_number ?? 'N/A' }}</div>
        </div>
        
        <div class="mb-3">
            <label class="text-muted small mb-1">Payment Date</label>
            <div>{{ $milkSupply->payment->payment_date->format('M d, Y') }}</div>
        </div>
        
        @if($milkSupply->payment->isPending() && auth()->user()->canApprovePayments())
        <div class="d-grid gap-2 mt-3">
            <form action="{{ route('payments.approve', $milkSupply->payment) }}" method="POST">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Approve this payment?')"
                        class="btn btn-success w-100">
                    <i class="fas fa-check me-2"></i>Approve Payment
                </button>
            </form>
            <form action="{{ route('payments.reject', $milkSupply->payment) }}" method="POST">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Reject this payment?')"
                        class="btn btn-danger w-100">
                    <i class="fas fa-times me-2"></i>Reject Payment
                </button>
            </form>
        </div>
        @endif
        
        <div class="text-center mt-3">
            <a href="{{ route('payments.show', $milkSupply->payment->id) }}" 
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-external-link-alt me-2"></i>View Payment Details
            </a>
        </div>
    </div>
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
.card {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.badge {
    font-size: 0.875em;
    padding: 0.5em 0.75em;
}

.btn {
    border-radius: 0.375rem;
}

.text-success {
    color: var(--farm-green) !important;
}

.bg-success {
    background-color: var(--farm-green) !important;
}

/* Responsive styles */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    h3, h4, h5 {
        font-size: 1rem;
    }
    
    .btn {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
}
</style>
@endpush