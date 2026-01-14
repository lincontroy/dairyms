@extends('layouts.app')

@section('title', 'Expense Management - Dairy Farm')
@section('page-title', 'Expense Management')

@section('breadcrumbs')
<li class="breadcrumb-item active">Expenses</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header with Stats -->
    @if(auth()->user()->canViewExpenseTotals())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h3 class="mb-1">KSh {{ number_format($summary['total'] ?? 0, 2) }}</h3>
                            <small class="text-muted">Total Expenses</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="mb-1">KSh {{ number_format($summary['monthly_total'] ?? 0, 2) }}</h3>
                            <small class="text-muted">This Month</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="mb-1">{{ $summary['pending_count'] ?? 0 }}</h3>
                            <small class="text-muted">Pending Approvals</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="mb-1">{{ count($summary['by_category'] ?? []) }}</h3>
                            <small class="text-muted">Categories</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Category Distribution (Admin Only) -->
    @if(auth()->user()->canViewExpenseTotals() && isset($summary['by_category']) && $summary['by_category']->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Expense Distribution by Category
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($summary['by_category'] as $category)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-start border-{{ [
                                'Animal Feed' => 'success',
                                'Veterinary' => 'danger',
                                'Labor' => 'warning',
                                'Equipment' => 'info'
                            ][$category->category] ?? 'secondary' }} border-4">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $category->category }}</h6>
                                    <h4 class="text-primary">KSh {{ number_format($category->total, 2) }}</h4>
                                    <small class="text-muted">
                                        {{ number_format(($category->total / $summary['total']) * 100, 1) }}% of total
                                    </small>
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

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('expenses.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </a>
                                @if(auth()->user()->canManageExpenses())
                                <a href="{{ route('expenses.create') }}" class="btn btn-success float-end">
                                    <i class="fas fa-plus me-1"></i> Add Expense
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Expense Records
                    </h6>
                    <span class="badge bg-secondary">{{ $expenses->total() }} records</span>
                </div>
                <div class="card-body">
                    @if($expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Recorded By</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->date->format('M d, Y') }}</td>
                                    <td>
                                        <strong>{{ $expense->description }}</strong>
                                        @if($expense->supplier)
                                        <br><small class="text-muted">Supplier: {{ $expense->supplier->name }}</small>
                                        @endif
                                        @if($expense->notes)
                                        <br><small class="text-info">{{ Str::limit($expense->notes, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ [
                                            'Animal Feed' => 'success',
                                            'Veterinary' => 'danger',
                                            'Labor' => 'warning',
                                            'Equipment' => 'info',
                                            'Utilities' => 'primary',
                                            'Transport' => 'secondary'
                                        ][$expense->category] ?? 'dark' }}">
                                            {{ $expense->category }}
                                        </span>
                                    </td>
                                    <td class="text-{{ $expense->amount > 10000 ? 'danger' : 'success' }}">
                                        <strong>KSh {{ number_format($expense->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}
                                        </span>
                                        @if($expense->reference_number)
                                        <br><small>{{ $expense->reference_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $expense->user->name }}
                                        <br><small class="text-muted">{{ $expense->created_at->format('M d, H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($expense->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($expense->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                        @else
                                        <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('expenses.show', $expense) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->canManageExpenses() && 
                                            (!$expense->is_approved || auth()->user()->isAdmin()))
                                        <a href="{{ route('expenses.edit', $expense) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if(auth()->user()->canApproveExpenses() && $expense->is_pending)
                                        <form action="{{ route('expenses.approve', $expense) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-success"
                                                    title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('expenses.reject', $expense) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if(auth()->user()->isAdmin())
                                        <form action="{{ route('expenses.destroy', $expense) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this expense?')"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $expenses->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5>No expenses found</h5>
                        <p class="text-muted">No expense records match your criteria.</p>
                        @if(auth()->user()->canManageExpenses())
                        <a href="{{ route('expenses.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Record Your First Expense
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
    .card.bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .border-start.border-success { border-left-width: 4px !important; }
    .border-start.border-danger { border-left-width: 4px !important; }
    .border-start.border-warning { border-left-width: 4px !important; }
    .border-start.border-info { border-left-width: 4px !important; }
</style>
@endpush