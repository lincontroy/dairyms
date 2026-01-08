@extends('layouts.app')

@section('title', 'Record Milk Supply - Dairy Farm Management')
@section('page-title', 'Record Milk Supply')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-supplies.index') }}">Milk Supplies</a>
    </li>
    <li class="breadcrumb-item active">Record New</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-wine-bottle me-2"></i>
                        Record Milk Supply
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Today's available milk: <strong>{{ number_format($availableMilk, 2) }} L</strong>
                    </div>
                    
                    <form action="{{ route('milk-supplies.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supplier_id" class="form-label">Supplier *</label>
                                <select name="supplier_id" id="supplier_id" 
                                        class="form-control @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                            data-rate="{{ $supplier->rate_per_liter }}">
                                            {{ $supplier->name }} - KSh {{ number_format($supplier->rate_per_liter, 2) }}/L
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date *</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity_liters" class="form-label">Quantity (Liters) *</label>
                                <input type="number" step="0.01" min="0.01" 
                                       class="form-control @error('quantity_liters') is-invalid @enderror" 
                                       id="quantity_liters" name="quantity_liters" 
                                       value="{{ old('quantity_liters') }}" required>
                                @error('quantity_liters')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rate_per_liter" class="form-label">Rate per Liter (KSh) *</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('rate_per_liter') is-invalid @enderror" 
                                       id="rate_per_liter" name="rate_per_liter" 
                                       value="{{ old('rate_per_liter') }}" required>
                                <small class="text-muted">Default rate from selected supplier</small>
                                @error('rate_per_liter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="waste_liters" class="form-label">Waste (Liters)</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('waste_liters') is-invalid @enderror" 
                                       id="waste_liters" name="waste_liters" 
                                       value="{{ old('waste_liters', 0) }}">
                                @error('waste_liters')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="mt-4">
                                    <div class="alert alert-light">
                                        <strong>Total Amount:</strong> 
                                        <span id="totalAmount">KSh 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('milk-supplies.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Record Supply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity_liters');
    const rateInput = document.getElementById('rate_per_liter');
    const totalAmountSpan = document.getElementById('totalAmount');
    const supplierSelect = document.getElementById('supplier_id');
    
    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const total = quantity * rate;
        totalAmountSpan.textContent = 'KSh ' + total.toFixed(2);
    }
    
    quantityInput.addEventListener('input', calculateTotal);
    rateInput.addEventListener('input', calculateTotal);
    
    // Set default rate when supplier is selected
    supplierSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const rate = selectedOption.dataset.rate || 0;
            rateInput.value = rate;
            calculateTotal();
        }
    });
    
    // Initial calculation
    calculateTotal();
    
    // Set rate if supplier is pre-selected
    if (supplierSelect.value) {
        supplierSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush