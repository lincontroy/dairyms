@extends('layouts.app')

@section('title', 'Supplier Details - Dairy Farm Management')
@section('page-title', 'Supplier Details')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('suppliers.index') }}">Suppliers</a>
    </li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Supplier Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-truck me-2"></i>
                        Supplier Information
                    </h5>
                    <span class="badge bg-{{ $supplier->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($supplier->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Supplier Name</label>
                                <h4 class="mb-0">{{ $supplier->name }}</h4>
                                @if($supplier->company_name)
                                    <p class="text-muted mb-0">{{ $supplier->company_name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Rate per Liter</label>
                                <h3 class="text-success mb-0">KSh {{ number_format($supplier->rate_per_liter, 2) }}</h3>
                                <small class="text-muted">{{ ucfirst($supplier->payment_frequency) }} payments</small>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <h6 class="mb-3 border-bottom pb-2">Contact Information</h6>
                    <div class="row mb-4">
                        @if($supplier->contact_person)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-user me-2 text-muted"></i>
                            <strong>Contact Person:</strong> {{ $supplier->contact_person }}
                        </div>
                        @endif
                        
                        @if($supplier->phone)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-phone me-2 text-muted"></i>
                            <strong>Phone:</strong> {{ $supplier->phone }}
                        </div>
                        @endif
                        
                        @if($supplier->email)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-envelope me-2 text-muted"></i>
                            <strong>Email:</strong> {{ $supplier->email }}
                        </div>
                        @endif
                        
                        @if($supplier->address)
                        <div class="col-12 mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                            <strong>Address:</strong> {{ $supplier->address }}
                        </div>
                        @endif
                    </div>

                    <!-- Contract Information -->
                    <h6 class="mb-3 border-bottom pb-2">Contract Information</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-calendar-alt me-2 text-muted"></i>
                            <strong>Contract Start:</strong> {{ $supplier->contract_start_date->format('M d, Y') }}
                        </div>
                        
                        @if($supplier->contract_end_date)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-calendar-times me-2 text-muted"></i>
                            <strong>Contract End:</strong> {{ $supplier->contract_end_date->format('M d, Y') }}
                        </div>
                        @endif
                        
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-money-bill-wave me-2 text-muted"></i>
                            <strong>Payment Frequency:</strong> {{ ucfirst($supplier->payment_frequency) }}
                        </div>
                    </div>

                    <!-- Bank Information -->
                    @if($supplier->bank_name || $supplier->bank_account || $supplier->tax_number)
                    <h6 class="mb-3 border-bottom pb-2">Bank & Tax Information</h6>
                    <div class="row mb-4">
                        @if($supplier->bank_name)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-university me-2 text-muted"></i>
                            <strong>Bank Name:</strong> {{ $supplier->bank_name }}
                        </div>
                        @endif
                        
                        @if($supplier->bank_account)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-credit-card me-2 text-muted"></i>
                            <strong>Account Number:</strong> {{ $supplier->bank_account }}
                        </div>
                        @endif
                        
                        @if($supplier->tax_number)
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-file-invoice me-2 text-muted"></i>
                            <strong>Tax Number:</strong> {{ $supplier->tax_number }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($supplier->notes)
                    <h6 class="mb-3 border-bottom pb-2">Notes</h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            {{ $supplier->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Milk Supplies -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-wine-bottle me-2"></i>
                        Recent Milk Supplies
                    </h5>
                    <a href="{{ route('milk-supplies.create', ['supplier_id' => $supplier->id]) }}" 
                       class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i>Record Supply
                    </a>
                </div>
                <div class="card-body">
                    @if($supplier->milkSupplies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-end">Quantity (L)</th>
                                        <th class="text-end">Waste (L)</th>
                                        <th class="text-end">Rate</th>
                                        <th class="text-end">Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->milkSupplies->sortByDesc('date')->take(10) as $supply)
                                    <tr>
                                        <td>{{ $supply->date->format('M d, Y') }}</td>
                                        <td class="text-end">{{ number_format($supply->quantity_liters, 1) }}</td>
                                        <td class="text-end">
                                            @if($supply->waste_liters > 0)
                                                <span class="text-danger">{{ number_format($supply->waste_liters, 1) }}</span>
                                            @else
                                                <span class="text-muted">0.0</span>
                                            @endif
                                        </td>
                                        <td class="text-end">KSh {{ number_format($supply->rate_per_liter, 2) }}</td>
                                        <td class="text-end">
                                            <strong>KSh {{ number_format($supply->total_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($supply->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($supply->status === 'recorded')
                                                <span class="badge bg-info">Recorded</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('milk-supplies.show', $supply) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('milk-supplies.index', ['supplier_id' => $supplier->id]) }}" 
                               class="btn btn-outline-primary">
                                View All Supplies
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-wine-bottle fa-3x text-muted mb-3"></i>
                            <h5>No Milk Supplies Yet</h5>
                            <p class="text-muted">This supplier hasn't received any milk supplies yet.</p>
                            <a href="{{ route('milk-supplies.create', ['supplier_id' => $supplier->id]) }}" 
                               class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Record First Supply
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Total Milk Supplied</label>
                        <h2 class="text-primary mb-0">{{ number_format($supplier->total_supplied, 2) }} L</h2>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Total Amount</label>
                        <h2 class="text-success mb-0">KSh {{ number_format($supplier->milkSupplies->sum('total_amount'), 2) }}</h2>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Balance Due</label>
                        <h2 class="{{ $supplier->balance > 0 ? 'text-danger' : 'text-success' }} mb-0">
                            KSh {{ number_format($supplier->balance, 2) }}
                        </h2>
                        @if($supplier->balance > 0)
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Outstanding balance
                            </small>
                        @else
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                All payments settled
                            </small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Current Rate</label>
                        <h4 class="mb-0">KSh {{ number_format($supplier->rate_per_liter, 2) }} / L</h4>
                    </div>
                    
                    <div>
                        <label class="text-muted small mb-1">Payment History</label>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                <span>Total Payments</span>
                                <span class="badge bg-info">{{ $supplier->payments->count() }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                <span>Approved Payments</span>
                                <span class="badge bg-success">{{ $supplier->payments->where('status', 'approved')->count() }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                <span>Pending Payments</span>
                                <span class="badge bg-warning">{{ $supplier->payments->where('status', 'pending')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($supplier->balance > 0)
                        <a href="{{ route('payments.create', ['supplier_id' => $supplier->id]) }}" 
                           class="btn btn-success">
                            <i class="fas fa-money-bill-wave me-2"></i>Create Payment
                        </a>
                        @endif
                        
                        <a href="{{ route('milk-supplies.create', ['supplier_id' => $supplier->id]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Record Milk Supply
                        </a>
                        
                        <a href="{{ route('payments.index', ['supplier_id' => $supplier->id]) }}" 
                           class="btn btn-info">
                            <i class="fas fa-history me-2"></i>View Payment History
                        </a>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('suppliers.edit', $supplier) }}" 
                               class="btn btn-warning flex-fill">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                            
                            @if($supplier->status === 'active')
                            <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="flex-fill">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" 
                                        onclick="return confirm('Deactivate this supplier?')"
                                        class="btn btn-outline-warning w-100">
                                    <i class="fas fa-pause me-2"></i>Deactivate
                                </button>
                            </form>
                            @else
                            <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="flex-fill">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" 
                                        onclick="return confirm('Activate this supplier?')"
                                        class="btn btn-outline-success w-100">
                                    <i class="fas fa-play me-2"></i>Activate
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Recent Payments
                    </h5>
                </div>
                <div class="card-body">
                    @if($supplier->payments->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($supplier->payments->sortByDesc('payment_date')->take(5) as $payment)
                            <div class="list-group-item px-0 py-2 border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>KSh {{ number_format($payment->amount, 2) }}</strong>
                                        <div class="small text-muted">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if($payment->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                        <div class="mt-1">
                                            <a href="{{ route('payments.show', $payment) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('payments.index', ['supplier_id' => $supplier->id]) }}" 
                               class="btn btn-sm btn-outline-info">
                                View All Payments
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-money-bill-wave fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No payment records</p>
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

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
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
    
    h2 {
        font-size: 1.5rem;
    }
    
    h4 {
        font-size: 1.25rem;
    }
    
    .btn {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here
    console.log('Supplier details page loaded');
});
</script>
@endpush