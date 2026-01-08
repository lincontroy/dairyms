@extends('layouts.app')

@section('title', 'Edit Milk Supply - Dairy Farm Management')
@section('page-title', 'Edit Milk Supply')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-supplies.index') }}">Milk Supplies</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Milk Supply Record
                    </h5>
                </div>
                <div class="card-body">
                    @if($milkSupply->status === 'approved')
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This record is already approved and cannot be edited.
                        </div>
                    @endif
                    
                    <form action="{{ route('milk-supplies.update', $milkSupply) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supplier_id" class="form-label">Supplier *</label>
                                <select name="supplier_id" id="supplier_id" 
                                        class="form-control @error('supplier_id') is-invalid @enderror" 
                                        {{ $milkSupply->status === 'approved' ? 'disabled' : 'required' }}>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                            {{ old('supplier_id', $milkSupply->supplier_id) == $supplier->id ? 'selected' : '' }}
                                            data-rate="{{ $supplier->rate_per_liter }}">
                                            {{ $supplier->name }} - KSh {{ number_format($supplier->rate_per_liter, 2) }}/L
                                        </option>
                                    @endforeach
                                </select>
                                @if($milkSupply->status !== 'approved')
                                    <input type="hidden" name="supplier_id" value="{{ old('supplier_id', $milkSupply->supplier_id) }}">
                                @endif
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date *</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                       id="date" name="date" 
                                       value="{{ old('date', $milkSupply->date->format('Y-m-d')) }}" 
                                       {{ $milkSupply->status === 'approved' ? 'disabled' : 'required' }}>
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
                                       value="{{ old('quantity_liters', $milkSupply->quantity_liters) }}" 
                                       {{ $milkSupply->status === 'approved' ? 'disabled' : 'required' }}>
                                @error('quantity_liters')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rate_per_liter" class="form-label">Rate per Liter (KSh) *</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('rate_per_liter') is-invalid @enderror" 
                                       id="rate_per_liter" name="rate_per_liter" 
                                       value="{{ old('rate_per_liter', $milkSupply->rate_per_liter) }}" 
                                       {{ $milkSupply->status === 'approved' ? 'disabled' : 'required' }}>
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
                                       value="{{ old('waste_liters', $milkSupply->waste_liters) }}"
                                       {{ $milkSupply->status === 'approved' ? 'disabled' : '' }}>
                                @error('waste_liters')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="mt-4">
                                    <div class="alert alert-light">
                                        <strong>Total Amount:</strong> 
                                        <span id="totalAmount">KSh {{ number_format($milkSupply->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3"
                                      {{ $milkSupply->status === 'approved' ? 'disabled' : '' }}>{{ old('notes', $milkSupply->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('milk-supplies.show', $milkSupply) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            @if($milkSupply->status !== 'approved')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Record
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
    
    if (quantityInput && rateInput) {
        quantityInput.addEventListener('input', calculateTotal);
        rateInput.addEventListener('input', calculateTotal);
        
        // Set default rate when supplier is selected
        if (supplierSelect) {
            supplierSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    const rate = selectedOption.dataset.rate || 0;
                    rateInput.value = rate;
                    calculateTotal();
                }
            });
        }
        
        // Initial calculation
        calculateTotal();
    }
});
</script>
@endpush
@endsection