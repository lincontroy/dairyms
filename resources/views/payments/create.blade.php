@extends('layouts.app')

@section('title', 'Create Payment - Dairy Farm Management')
@section('page-title', 'Create Supplier Payment')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('payments.index') }}">Payments</a>
    </li>
    <li class="breadcrumb-item active">Create New</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Create Payment Record
                    </h5>
                </div>
                <div class="card-body">
                    @if($suppliersWithBalance->count() > 0)
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Suppliers with pending balances:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($suppliersWithBalance->take(3) as $supplier)
                                    <li>{{ $supplier->name }}: KSh {{ number_format($supplier->balance, 2) }}</li>
                                @endforeach
                                @if($suppliersWithBalance->count() > 3)
                                    <li>... and {{ $suppliersWithBalance->count() - 3 }} more</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('payments.store') }}" method="POST">
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
                                            data-balance="{{ $supplier->balance }}">
                                            {{ $supplier->name }} 
                                            @if($supplier->balance > 0)
                                                (Balance: KSh {{ number_format($supplier->balance, 2) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="payment_date" class="form-label">Payment Date *</label>
                                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                       id="payment_date" name="payment_date" 
                                       value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount (KSh) *</label>
                                <input type="number" step="0.01" min="0.01" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="balanceInfo"></small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select name="payment_method" id="payment_method" 
                                        class="form-control @error('payment_method') is-invalid @enderror" required>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                       id="reference_number" name="reference_number" 
                                       value="{{ old('reference_number') }}">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="milk_supply_id" class="form-label">Milk Supply (Optional)</label>
                                <select name="milk_supply_id" id="milk_supply_id" 
                                        class="form-control @error('milk_supply_id') is-invalid @enderror">
                                    <option value="">Select Milk Supply Record</option>
                                    @if(old('supplier_id') || $suppliers->first())
                                        @php
                                            $selectedSupplierId = old('supplier_id', $suppliers->first()->id ?? null);
                                            $unpaidSupplies = $selectedSupplierId ? 
                                                \App\Models\MilkSupply::where('supplier_id', $selectedSupplierId)
                                                    ->whereDoesntHave('payment')
                                                    ->where('status', 'approved')
                                                    ->get() : [];
                                        @endphp
                                        @foreach($unpaidSupplies as $supply)
                                            <option value="{{ $supply->id }}" 
                                                {{ old('milk_supply_id') == $supply->id ? 'selected' : '' }}>
                                                {{ $supply->date->format('M d, Y') }}: {{ number_format($supply->quantity_liters, 2) }}L - KSh {{ number_format($supply->total_amount, 2) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('milk_supply_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_period_start" class="form-label">Payment Period Start *</label>
                                <input type="date" class="form-control @error('payment_period_start') is-invalid @enderror" 
                                       id="payment_period_start" name="payment_period_start" 
                                       value="{{ old('payment_period_start', now()->subMonth()->startOfMonth()->format('Y-m-d')) }}" required>
                                @error('payment_period_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="payment_period_end" class="form-label">Payment Period End *</label>
                                <input type="date" class="form-control @error('payment_period_end') is-invalid @enderror" 
                                       id="payment_period_end" name="payment_period_end" 
                                       value="{{ old('payment_period_end', now()->subMonth()->endOfMonth()->format('Y-m-d')) }}" required>
                                @error('payment_period_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Create Payment
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
    const supplierSelect = document.getElementById('supplier_id');
    const amountInput = document.getElementById('amount');
    const balanceInfo = document.getElementById('balanceInfo');
    const milkSupplySelect = document.getElementById('milk_supply_id');
    
    // Update balance info when supplier changes
    supplierSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const balance = selectedOption.dataset.balance || 0;
        
        if (balance > 0) {
            balanceInfo.textContent = `Supplier balance: KSh ${parseFloat(balance).toFixed(2)}`;
            balanceInfo.className = 'text-muted';
        } else {
            balanceInfo.textContent = 'No outstanding balance';
            balanceInfo.className = 'text-success';
        }
        
        // // Reload page with selected supplier to get unpaid supplies
        // if (this.value) {
        //     window.location.href = '{{ route("payments.create") }}?supplier_id=' + this.value;
        // }
    });
    
    // Auto-set amount when milk supply is selected
    milkSupplySelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const amount = selectedOption.textContent.split('KSh ')[1];
            if (amount) {
                amountInput.value = amount.trim();
            }
        }
    });
    
    // Initialize balance info
    if (supplierSelect.value) {
        const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
        const balance = selectedOption.dataset.balance || 0;
        
        if (balance > 0) {
            balanceInfo.textContent = `Supplier balance: KSh ${parseFloat(balance).toFixed(2)}`;
        }
    }
});
</script>
@endpush