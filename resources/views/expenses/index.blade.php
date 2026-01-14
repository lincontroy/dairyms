@extends('layouts.app')

@section('title', 'Expense Management - Dairy Farm')
@section('page-title', 'Expense Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Expenses</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1"><i class="fas fa-receipt text-primary me-2"></i>Expense Management</h4>
                    <p class="text-muted mb-0">Track and manage farm expenses efficiently</p>
                </div>
                @if(auth()->user()->canManageExpenses())
                <a href="{{ route('expenses.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i> New Expense
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    @if(auth()->user()->canViewExpenseTotals())
    <div class="row mb-4">
        <!-- Total Expenses -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Expenses</h6>
                            <h3 class="mb-0">KSh {{ number_format($summary['total'] ?? 0, 0) }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i> All Time
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">This Month</h6>
                            <h3 class="mb-0">KSh {{ number_format($summary['monthly_total'] ?? 0, 0) }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i> {{ now()->format('F Y') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0">{{ $summary['pending_count'] ?? 0 }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-exclamation-circle me-1"></i> Need approval
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Categories</h6>
                            <h3 class="mb-0">{{ count($summary['by_category'] ?? []) }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-tags fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-layer-group me-1"></i> Expense types
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Category Distribution -->
    @if(auth()->user()->canViewExpenseTotals() && isset($summary['by_category']) && $summary['by_category']->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-pie text-info me-2"></i>Expense Distribution
                    </h6>
                    <small class="text-muted">Current Month</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($summary['by_category'] as $category)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card border h-100 hover-shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge rounded-pill fs-7 px-3 py-2 bg-{{
                                            [
                                                'Animal Feed' => 'success-subtle text-success-emphasis',
                                                'Veterinary' => 'danger-subtle text-danger-emphasis',
                                                'Labor' => 'warning-subtle text-warning-emphasis',
                                                'Equipment' => 'info-subtle text-info-emphasis',
                                                'Utilities' => 'primary-subtle text-primary-emphasis',
                                                'Transport' => 'secondary-subtle text-secondary-emphasis'
                                            ][$category->category] ?? 'dark-subtle text-dark-emphasis'
                                        }}">
                                            {{ $category->category }}
                                        </span>
                                        <span class="text-muted fs-7">
                                            {{ number_format(($category->total / $summary['total']) * 100, 1) }}%
                                        </span>
                                    </div>
                                    <h4 class="mb-1">KSh {{ number_format($category->total, 0) }}</h4>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-{{
                                            [
                                                'Animal Feed' => 'success',
                                                'Veterinary' => 'danger',
                                                'Labor' => 'warning',
                                                'Equipment' => 'info'
                                            ][$category->category] ?? 'secondary'
                                        }}" style="width: {{ ($category->total / $summary['total']) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-filter text-primary me-2"></i>Filter Expenses
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('expenses.index') }}" class="row g-3">
                        <!-- Category Filter -->
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-muted small">Category</label>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-medium text-muted small">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-muted small">Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" 
                                   value="{{ request('start_date') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium text-muted small">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" 
                                   value="{{ request('end_date') }}">
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-1">
                            <label class="form-label fw-medium text-muted small">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm" 
                                   title="Reset filters">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-list text-primary me-2"></i>Expense Records
                        </h6>
                        @if(request()->has('category') || request()->has('status') || request()->has('start_date'))
                        <small class="text-muted">Filtered results</small>
                        @endif
                    </div>
                    <div>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            {{ $expenses->total() }} {{ Str::plural('record', $expenses->total()) }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 120px;">Date</th>
                                    <th>Description</th>
                                    <th style="width: 140px;">Category</th>
                                    <th style="width: 120px;">Amount</th>
                                    <th style="width: 150px;">Payment</th>
                                    <th style="width: 160px;">Recorded By</th>
                                    <th style="width: 100px;">Status</th>
                                    <th class="text-center pe-4" style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr class="border-bottom">
                                    <!-- Date -->
                                    <td class="ps-4">
                                        <div class="text-muted small">{{ $expense->date->format('M d, Y') }}</div>
                                        <div class="text-muted smaller">{{ $expense->date->format('D') }}</div>
                                    </td>

                                    <!-- Description -->
                                    <td>
                                        <div class="fw-medium">{{ Str::limit($expense->description, 40) }}</div>
                                        @if($expense->supplier)
                                        <div class="text-muted small">
                                            <i class="fas fa-truck me-1"></i>{{ $expense->supplier->name }}
                                        </div>
                                        @endif
                                        @if($expense->notes)
                                        <div class="text-info small mt-1">
                                            <i class="fas fa-sticky-note me-1"></i>{{ Str::limit($expense->notes, 40) }}
                                        </div>
                                        @endif
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 fs-7 bg-{{
                                            [
                                                'Animal Feed' => 'success-subtle text-success-emphasis',
                                                'Veterinary' => 'danger-subtle text-danger-emphasis',
                                                'Labor' => 'warning-subtle text-warning-emphasis',
                                                'Equipment' => 'info-subtle text-info-emphasis',
                                                'Utilities' => 'primary-subtle text-primary-emphasis',
                                                'Transport' => 'secondary-subtle text-secondary-emphasis'
                                            ][$expense->category] ?? 'dark-subtle text-dark-emphasis'
                                        }}">
                                            {{ $expense->category }}
                                        </span>
                                    </td>

                                    <!-- Amount -->
                                    <td>
                                        <div class="fw-bold fs-6 text-{{ $expense->amount > 10000 ? 'danger' : 'success' }}">
                                            KSh {{ number_format($expense->amount, 0) }}
                                        </div>
                                        <div class="text-muted small">
                                            @if($expense->amount > 10000)
                                            <i class="fas fa-exclamation-triangle me-1"></i>High amount
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Payment Method -->
                                    <td>
                                        <div class="badge bg-light text-dark rounded-pill px-3 py-2 fs-7">
                                            <i class="fas fa-{{ 
                                                $expense->payment_method == 'mpesa' ? 'mobile-alt' : 
                                                ($expense->payment_method == 'bank_transfer' ? 'university' : 
                                                ($expense->payment_method == 'cheque' ? 'file-invoice-dollar' : 'money-bill'))
                                            }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}
                                        </div>
                                        @if($expense->reference_number)
                                        <div class="text-muted small mt-1">
                                            <small>Ref: {{ $expense->reference_number }}</small>
                                        </div>
                                        @endif
                                    </td>

                                    <!-- Recorded By -->
                                    <td>
                                        <div class="fw-medium">{{ $expense->user->name }}</div>
                                        <div class="text-muted small">
                                            {{ $expense->created_at->format('M d, H:i') }}
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if($expense->status == 'pending')
                                        <span class="badge rounded-pill px-3 py-2 fs-7 bg-warning-subtle text-warning-emphasis">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                        @elseif($expense->status == 'approved')
                                        <span class="badge rounded-pill px-3 py-2 fs-7 bg-success-subtle text-success-emphasis">
                                            <i class="fas fa-check-circle me-1"></i>Approved
                                        </span>
                                        @else
                                        <span class="badge rounded-pill px-3 py-2 fs-7 bg-danger-subtle text-danger-emphasis">
                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                        </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center pe-4">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View -->
                                            <a href="{{ route('expenses.show', $expense) }}" 
                                               class="btn btn-outline-primary" 
                                               title="View Details"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- Edit (Conditional) -->
                                            @if(auth()->user()->canManageExpenses() && 
                                                (!$expense->is_approved || auth()->user()->isAdmin()))
                                            <a href="{{ route('expenses.edit', $expense) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Edit"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif

                                            <!-- Approve/Reject (Admin only for pending) -->
                                            @if(auth()->user()->canApproveExpenses() && $expense->is_pending)
                                            <form action="{{ route('expenses.approve', $expense) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-success"
                                                        title="Approve"
                                                        data-bs-toggle="tooltip"
                                                        onclick="return confirm('Approve this expense?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('expenses.reject', $expense) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-danger"
                                                        title="Reject"
                                                        data-bs-toggle="tooltip"
                                                        onclick="return confirm('Reject this expense?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <!-- Delete (Admin only) -->
                                            @if(auth()->user()->isAdmin())
                                            <form action="{{ route('expenses.destroy', $expense) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger"
                                                        title="Delete"
                                                        data-bs-toggle="tooltip"
                                                        onclick="return confirm('Are you sure? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
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
                    @if($expenses->hasPages())
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $expenses->firstItem() }} to {{ $expenses->lastItem() }} of {{ $expenses->total() }} entries
                            </div>
                            <div>
                                {{ $expenses->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-receipt fa-4x text-light" style="opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-3">No expenses found</h5>
                        <p class="text-muted mb-4">
                            @if(request()->hasAny(['category', 'status', 'start_date', 'end_date']))
                            No expenses match your filter criteria.
                            @else
                            You haven't recorded any expenses yet.
                            @endif
                        </p>
                        @if(auth()->user()->canManageExpenses())
                        <a href="{{ route('expenses.create') }}" class="btn btn-success px-4">
                            <i class="fas fa-plus-circle me-2"></i> Record Your First Expense
                        </a>
                        @endif
                        @if(request()->hasAny(['category', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary ms-2">
                            Clear Filters
                        </a>
                        @endif
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
    /* Custom styles for expense management */
    .card {
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 1rem 0.75rem;
        background-color: #f8f9fa;
    }
    
    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.02);
    }
    
    .table tbody tr:last-child {
        border-bottom: none;
    }
    
    .border-start {
        border-left-width: 4px !important;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .progress {
        border-radius: 3px;
    }
    
    .progress-bar {
        border-radius: 3px;
    }
    
    /* Hover effect for category cards */
    .hover-shadow-sm:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header h6 {
            font-size: 1rem;
        }
        
        .table th, .table td {
            padding: 0.75rem 0.5rem;
        }
        
        .btn-group {
            flex-wrap: wrap;
            gap: 2px;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Set default dates for filters
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');
        
        if (!startDateInput.value && !endDateInput.value) {
            // Set default to current month
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            startDateInput.value = firstDay.toISOString().split('T')[0];
            endDateInput.value = lastDay.toISOString().split('T')[0];
        }
        
        // Auto-submit form on some filter changes
        document.querySelectorAll('select[name="category"], select[name="status"]').forEach(select => {
            select.addEventListener('change', function() {
                if (this.value) {
                    this.form.submit();
                }
            });
        });
        
        // Format currency on page
        document.querySelectorAll('.currency-amount').forEach(element => {
            const amount = parseFloat(element.textContent.replace(/[^\d.-]/g, ''));
            if (!isNaN(amount)) {
                element.textContent = new Intl.NumberFormat('en-KE', {
                    style: 'currency',
                    currency: 'KES',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }
        });
    });
</script>
@endpush