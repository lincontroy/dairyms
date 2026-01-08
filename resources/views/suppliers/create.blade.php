@extends('layouts.app')

@section('title', 'Add Supplier - Dairy Farm Management')
@section('page-title', 'Add New Supplier')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('suppliers.index') }}">Suppliers</a>
    </li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Supplier Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Supplier Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name') }}">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                       id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rate_per_liter" class="form-label">Rate per Liter (KSh) *</label>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('rate_per_liter') is-invalid @enderror" 
                                       id="rate_per_liter" name="rate_per_liter" 
                                       value="{{ old('rate_per_liter', 50) }}" required>
                                @error('rate_per_liter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_frequency" class="form-label">Payment Frequency *</label>
                                <select class="form-control @error('payment_frequency') is-invalid @enderror" 
                                        id="payment_frequency" name="payment_frequency" required>
                                    <option value="daily" {{ old('payment_frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('payment_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('payment_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                                @error('payment_frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contract_start_date" class="form-label">Contract Start Date *</label>
                                <input type="date" class="form-control @error('contract_start_date') is-invalid @enderror" 
                                       id="contract_start_date" name="contract_start_date" 
                                       value="{{ old('contract_start_date', now()->format('Y-m-d')) }}" required>
                                @error('contract_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contract_end_date" class="form-label">Contract End Date</label>
                                <input type="date" class="form-control @error('contract_end_date') is-invalid @enderror" 
                                       id="contract_end_date" name="contract_end_date" 
                                       value="{{ old('contract_end_date') }}">
                                @error('contract_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                       id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="bank_account" class="form-label">Bank Account Number</label>
                                <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                       id="bank_account" name="bank_account" value="{{ old('bank_account') }}">
                                @error('bank_account')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection