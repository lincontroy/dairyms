@extends('layouts.app')

@section('title', 'Payments - Dairy Farm Management')
@section('page-title', 'Supplier Payments')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Payments</h6>
                            <h3 class="mb-0">KSh {{ number_format($pendingAmount, 2) }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Approved Payments</h6>
                            <h3 class="mb-0">KSh {{ number_format($approvedAmount, 2) }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
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
                            <h6 class="text-white-50 mb-1">Total Suppliers</h6>
                            <h3 class="mb-0">{{ $totalSuppliers }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
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
                            <h6 class="text-white-50 mb-1">This Month</h6>
                            <h3 class="mb-0">
                                KSh {{ number_format(\App\Models\SupplierPayment::whereMonth('payment_date', now()->month)->where('status', 'approved')->sum('amount'), 2) }}
                            </h3>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
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
                        <h5 class="mb-0">Payment Management</h5>
                        <div>
                            <a href="{{ route('payments.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Create Payment
                            </a>
                            @if(auth()->user()->canApprovePayments())
                            <button type="button" class="btn btn-primary ms-2" id="bulkApproveBtn">
                                <i class="fas fa-check-double me-2"></i>Bulk Approve
                            </button>
                            <form action="{{ route('payments.generate') }}" method="POST" class="d-inline ms-2">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Generate monthly payments for all suppliers?')"
                                        class="btn btn-info">
                                    <i class="fas fa-cogs me-2"></i>Generate Monthly
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('payments.index') }}" method="GET" class="row g-3">
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
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                
                <div class="col-md-12 d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-money-bill-wave me-2"></i>
                Payment Records
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('payments.bulk-approve') }}" method="POST" id="bulkApproveForm">
                @csrf
                
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>Payment Date</th>
                                    <th>Supplier</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Period</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        @if($payment->status === 'pending')
                                            <input type="checkbox" name="payment_ids[]" 
                                                   value="{{ $payment->id }}" class="payment-checkbox">
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('suppliers.show', $payment->supplier_id) }}">
                                            <strong>{{ $payment->supplier->name }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <strong>KSh {{ number_format($payment->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                    </td>
                                    <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                    <td>
                                        {{ $payment->payment_period_start->format('M d') }} - 
                                        {{ $payment->payment_period_end->format('M d, Y') }}
                                    </td>
                                    <td>{{ $payment->creator->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($payment->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                            @if($payment->approver)
                                                <br>
                                                <small class="text-muted">by {{ $payment->approver->name }}</small>
                                            @endif
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('payments.show', $payment) }}" 
                                               class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($payment->status === 'pending' && auth()->user()->canApprovePayments())
                                                <form action="{{ route('payments.approve', $payment) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            onclick="return confirm('Approve this payment?')"
                                                            class="btn btn-outline-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('payments.reject', $payment) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            onclick="return confirm('Reject this payment?')"
                                                            class="btn btn-outline-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
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
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                        <h4>No Payment Records</h4>
                        <p class="text-muted">Create your first payment record to get started.</p>
                        <a href="{{ route('payments.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Create First Payment
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkApproveForm = document.getElementById('bulkApproveForm');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Bulk approve button
    if (bulkApproveBtn && bulkApproveForm) {
        bulkApproveBtn.addEventListener('click', function() {
            const selectedCount = document.querySelectorAll('.payment-checkbox:checked').length;
            
            if (selectedCount === 0) {
                alert('Please select at least one payment to approve.');
                return;
            }
            
            if (confirm(`Approve ${selectedCount} selected payment(s)?`)) {
                bulkApproveForm.submit();
            }
        });
    }
    
    // Update select all checkbox state
    if (checkboxes.length > 0) {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.payment-checkbox:checked').length === checkboxes.length;
                if (selectAll) selectAll.checked = allChecked;
            });
        });
    }
});
</script>
@endpush