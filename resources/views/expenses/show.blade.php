@extends('layouts.app')

@section('title', 'Expense Details - Dairy Farm')
@section('page-title', 'Expense Details')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Expense Details Card -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                        Expense Receipt
                    </h5>
                    <span class="badge bg-{{ $expense->status == 'approved' ? 'success' : ($expense->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($expense->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Date</h6>
                            <h5>{{ $expense->date->format('F j, Y') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Amount</h6>
                            <h2 class="text-{{ $expense->amount > 10000 ? 'danger' : 'success' }}">
                                KSh {{ number_format($expense->amount, 2) }}
                            </h2>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Category</h6>
                            <span class="badge bg-{{ [
                                'Animal Feed' => 'success',
                                'Veterinary' => 'danger',
                                'Labor' => 'warning',
                                'Equipment' => 'info'
                            ][$expense->category] ?? 'secondary' }} fs-6">
                                {{ $expense->category }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Method</h6>
                            <h5>{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</h5>
                            @if($expense->reference_number)
                            <small class="text-muted">Ref: {{ $expense->reference_number }}</small>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted">Description</h6>
                            <div class="p-3 bg-light rounded">
                                {{ $expense->description }}
                            </div>
                        </div>
                    </div>
                    
                    @if($expense->notes)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted">Additional Notes</h6>
                            <div class="p-3 bg-light rounded">
                                {{ $expense->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Recorded By</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle fa-2x text-muted me-2"></i>
                                <div>
                                    <strong>{{ $expense->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $expense->created_at->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                        @if($expense->supplier)
                        <div class="col-md-6">
                            <h6 class="text-muted">Supplier</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck fa-2x text-muted me-2"></i>
                                <div>
                                    <strong>{{ $expense->supplier->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $expense->supplier->phone }}</small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                        <div>
                            @if(auth()->user()->canManageExpenses() && 
                                (!$expense->is_approved || auth()->user()->isAdmin()))
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            @endif
                            @if(auth()->user()->canApproveExpenses() && $expense->is_pending)
                            <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('expenses.reject', $expense) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i> Reject
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection