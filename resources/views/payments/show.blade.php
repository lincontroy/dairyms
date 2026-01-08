@extends('layouts.app')

@section('title', 'Payment Details - Dairy Farm Management')
@section('page-title', 'Payment Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Payment #{{ $payment->reference_number }}</h4>
                            <p class="text-muted mb-0">Created on {{ $payment->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            @if($payment->status === 'pending' && auth()->user()->canApprovePayments())
                                <form action="{{ route('payments.approve', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Approve this payment?')" 
                                            class="btn btn-outline-success ms-2">
                                        <i class="fas fa-check me-2"></i>Approve
                                    </button>
                                </form>
                                <form action="{{ route('payments.reject', $payment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Reject this payment?')" 
                                            class="btn btn-outline-danger ms-2">
                                        <i class="fas fa-times me-2"></i>Reject
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Details -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Payment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Payment Status</label>
                            <div>
                                @if($payment->status === 'approved')
                                    <span class="badge bg-success fs-6">Approved</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning fs-6">Pending</span>
                                @else
                                    <span class="badge bg-danger fs-6">Rejected</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Payment Amount</label>
                            <h4 class="mb-0 text-primary">KSh {{ number_format($payment->amount, 2) }}</h4>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Payment Date</label>
                            <p class="mb-0 fw-semibold">
                                @if($payment->payment_date)
                                    {{ $payment->payment_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Payment Method</label>
                            <p class="mb-0">
                                @if($payment->payment_method)
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">Payment Period</label>
                            <p class="mb-0 fw-semibold">
                                @if($payment->payment_period_start && $payment->payment_period_end)
                                    {{ $payment->payment_period_start->format('M d, Y') }} 
                                    to 
                                    {{ $payment->payment_period_end->format('M d, Y') }}
                                    ({{ $payment->payment_period_start->diffInDays($payment->payment_period_end) + 1 }} days)
                                @else
                                    <span class="text-muted">Period not specified</span>
                                @endif
                            </p>
                        </div>
                        
                        @if($payment->notes)
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-muted">Notes</label>
                                <div class="border rounded p-3 bg-light">
                                    {{ $payment->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Associated Milk Supply (if any) -->
            @if($payment->milkSupply)
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cow me-2"></i>
                        Associated Milk Supply
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Supply Date</label>
                            <p class="mb-0">
                                @if($payment->milkSupply->supply_date)
                                    {{ $payment->milkSupply->supply_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Quantity</label>
                            <p class="mb-0">{{ $payment->milkSupply->quantity ?? 0 }} liters</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Price per Liter</label>
                            <p class="mb-0">KSh {{ number_format($payment->milkSupply->price_per_liter ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Total Amount</label>
                            <p class="mb-0 fw-semibold">KSh {{ number_format($payment->milkSupply->total_amount ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar Information -->
        <div class="col-md-4 mb-4">
            <!-- Supplier Information -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        Supplier Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $payment->supplier->name ?? 'Unknown Supplier' }}</h5>
                        <p class="text-muted mb-0">{{ $payment->supplier->code ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="mb-2">
                            <small class="text-muted">Phone:</small>
                            <p class="mb-0 fw-semibold">{{ $payment->supplier->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Email:</small>
                            <p class="mb-0 fw-semibold">{{ $payment->supplier->email ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Bank Account:</small>
                            <p class="mb-0 fw-semibold">{{ $payment->supplier->bank_account ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Bank Name:</small>
                            <p class="mb-0 fw-semibold">{{ $payment->supplier->bank_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($payment->supplier)
                    <div class="mt-3">
                        <a href="{{ route('suppliers.show', $payment->supplier) }}" 
                           class="btn btn-outline-primary w-100">
                            <i class="fas fa-external-link-alt me-2"></i>View Supplier Details
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Audit Information -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Audit Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Created By:</small>
                        <p class="mb-0 fw-semibold">
                            {{ $payment->creator->name ?? 'System' }}
                            <br>
                            <small class="text-muted">{{ $payment->created_at->format('M d, Y h:i A') }}</small>
                        </p>
                    </div>
                    
                    @if($payment->isApproved())
                        <div class="mb-3">
                            <small class="text-muted">Approved By:</small>
                            <p class="mb-0 fw-semibold">
                                {{ $payment->approver->name ?? 'N/A' }}
                                <br>
                                @if($payment->approved_at)
                                    <small class="text-muted">{{ $payment->approved_at->format('M d, Y h:i A') }}</small>
                                @else
                                    <small class="text-muted">Date not set</small>
                                @endif
                            </p>
                        </div>
                    @endif
                    
                    @if($payment->isRejected())
                        <div class="mb-3">
                            <small class="text-muted">Rejected By:</small>
                            <p class="mb-0 fw-semibold">
                                {{ $payment->approver->name ?? 'N/A' }}
                                <br>
                                @if($payment->approved_at)
                                    <small class="text-muted">{{ $payment->approved_at->format('M d, Y h:i A') }}</small>
                                @else
                                    <small class="text-muted">Date not set</small>
                                @endif
                            </p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <small class="text-muted">Last Updated:</small>
                        <p class="mb-0 fw-semibold">
                            {{ $payment->updated_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($payment->status === 'pending' && auth()->user()->can('edit', $payment))
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>Edit Payment
                            </a>
                        @endif
                        
                        <button type="button" class="btn btn-outline-info" id="printBtn">
                            <i class="fas fa-print me-2"></i>Print Receipt
                        </button>
                        
                        @if(auth()->user()->can('delete', $payment) && $payment->status === 'pending')
                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this payment?')"
                                        class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Delete Payment
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
    }
    .card-header {
        border-bottom: 1px solid #e0e0e0;
        background-color: #f8f9fa;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print receipt functionality
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            // You can implement a print view or use window.print() for now
            window.print();
        });
    }
    
    // Confirm rejection
    const rejectButtons = document.querySelectorAll('[onclick*="Reject this payment"]');
    rejectButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to reject this payment?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush