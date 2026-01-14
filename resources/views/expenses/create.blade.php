@extends('layouts.app')

@section('title', 'Record New Expense - Dairy Farm')
@section('page-title', 'Record New Expense')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Expenses</a></li>
<li class="breadcrumb-item active">New Expense</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave text-danger me-2"></i>
                        Record Farm Expense
                        @if(!auth()->user()->isAdmin())
                        <span class="badge bg-warning float-end">Requires Approval</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('expenses.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Date -->
                            <div class="col-md-6">
                                <label for="date" class="form-label">Expense Date *</label>
                                <input type="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       id="date" 
                                       name="date" 
                                       value="{{ old('date', date('Y-m-d')) }}"
                                       required>
                                @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount (KSh) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">KSh</span>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0"
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           value="{{ old('amount') }}"
                                           placeholder="0.00"
                                           required>
                                </div>
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" 
                                        name="category"
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Supplier (Optional) -->
                            <div class="col-md-6">
                                <label for="supplier_id" class="form-label">Supplier (Optional)</label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" 
                                        name="supplier_id">
                                    <option value="">No Supplier</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="2"
                                          placeholder="What was this expense for? Provide details..."
                                          required>{{ old('description') }}</textarea>
                                <small class="form-text text-muted">
                                    Be specific: e.g., "Bought 100kg of dairy meal from XYZ store"
                                </small>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" 
                                        name="payment_method"
                                        required>
                                    @foreach($paymentMethods as $key => $value)
                                    <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reference Number -->
                            <div class="col-md-6">
                                <label for="reference_number" class="form-label">Reference Number (Optional)</label>
                                <input type="text" 
                                       class="form-control @error('reference_number') is-invalid @enderror" 
                                       id="reference_number" 
                                       name="reference_number" 
                                       value="{{ old('reference_number') }}"
                                       placeholder="e.g., MPESA confirmation code, receipt number">
                                @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="2"
                                          placeholder="Any additional information...">{{ old('notes') }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Notice -->
                            <div class="col-12">
                                @if(!auth()->user()->isAdmin())
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> This expense will require approval from an administrator.
                                </div>
                                @else
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Note:</strong> As an administrator, this expense will be automatically approved.
                                </div>
                                @endif
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> 
                                        @if(auth()->user()->isAdmin())
                                        Save & Approve Expense
                                        @else
                                        Submit for Approval
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Expense Tips -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Quick Expense Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small>Always attach receipts when available</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small>Use specific descriptions</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small>Record expenses daily</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default date to today
    document.getElementById('date').value = new Date().toISOString().split('T')[0];
    
    // Format amount on blur
    const amountInput = document.getElementById('amount');
    amountInput.addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
});
</script>
@endpush