@extends('layouts.app')

@section('title', 'Suppliers - Dairy Farm Management')
@section('page-title', 'Suppliers Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Suppliers</li>
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
                            <h6 class="text-white-50 mb-1">Active Suppliers</h6>
                            <h3 class="mb-0">{{ \App\Models\Supplier::active()->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
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
                            <h6 class="text-white-50 mb-1">Total Supplied</h6>
                            <h3 class="mb-0">
                                {{ number_format(\App\Models\MilkSupply::sum('quantity_liters'), 2) }} L
                            </h3>
                        </div>
                        <div>
                            <i class="fas fa-wine-bottle fa-3x opacity-50"></i>
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
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Payments</h6>
                            <h3 class="mb-0">
                                KSh {{ number_format(\App\Models\SupplierPayment::pending()->sum('amount'), 2) }}
                            </h3>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
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
                        <h5 class="mb-0">Supplier Management</h5>
                        <div>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add New Supplier
                            </a>
                            <a href="{{ route('payments.index') }}" class="btn btn-primary ms-2">
                                <i class="fas fa-money-bill me-2"></i>Payments
                            </a>
                            <a href="{{ route('milk-supplies.index') }}" class="btn btn-info ms-2">
                                <i class="fas fa-wine-bottle me-2"></i>Milk Supplies
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-users me-2"></i>
                All Suppliers
            </h5>
        </div>
        <div class="card-body">
            @if($suppliers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Supplier Name</th>
                                <th>Contact Info</th>
                                <th>Rate/Liter</th>
                                <th>Total Supplied</th>
                                <th>Balance Due</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>#{{ str_pad($supplier->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <strong>{{ $supplier->name }}</strong>
                                    @if($supplier->company_name)
                                        <br><small class="text-muted">{{ $supplier->company_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->phone)
                                        <i class="fas fa-phone me-1"></i>{{ $supplier->phone }}<br>
                                    @endif
                                    @if($supplier->email)
                                        <i class="fas fa-envelope me-1"></i>{{ $supplier->email }}
                                    @endif
                                </td>
                                <td>
                                    <strong>KSh {{ number_format($supplier->rate_per_liter, 2) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ ucfirst($supplier->payment_frequency) }}</small>
                                </td>
                                <td>
                                    {{ number_format($supplier->total_supplied, 2) }} L
                                    <br>
                                    <small class="text-muted">KSh {{ number_format($supplier->milkSupplies()->sum('total_amount'), 2) }}</small>
                                </td>
                                <td>
                                    <strong class="{{ $supplier->balance > 0 ? 'text-danger' : 'text-success' }}">
                                        KSh {{ number_format($supplier->balance, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    @if($supplier->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('suppliers.show', $supplier) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirmDelete(event, 'supplier {{ $supplier->name }}')"
                                                    class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $suppliers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4>No Suppliers Found</h4>
                    <p class="text-muted">Add your first supplier to start recording milk supplies.</p>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add First Supplier
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, name) {
    if (!confirm(`Are you sure you want to delete ${name}? This action cannot be undone.`)) {
        event.preventDefault();
        return false;
    }
    return true;
}
</script>
@endpush