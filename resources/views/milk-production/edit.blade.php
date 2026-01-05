@extends('layouts.app')

@section('title', 'Edit Milk Production - Dairy Farm Management')
@section('page-title', 'Edit Milk Production')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-production.index') }}">Milk Production</a>
    </li>
    <li class="breadcrumb-item active">Edit Record</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Edit Milk Production Record
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('milk-production.update', $milkProduction) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Animal Selection -->
                        <div class="mb-4">
                            <label for="animal_id" class="form-label">
                                Animal <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('animal_id') is-invalid @enderror" 
                                    id="animal_id" 
                                    name="animal_id" 
                                    required>
                                <option value="">Select Animal</option>
                                @foreach($animals as $animal)
                                    <option value="{{ $animal->id }}" 
                                            {{ old('animal_id', $milkProduction->animal_id) == $animal->id ? 'selected' : '' }}>
                                        {{ $animal->animal_id }} - {{ $animal->name ?? 'Unnamed' }} 
                                        ({{ $animal->breed }})
                                    </option>
                                @endforeach
                            </select>
                            @error('animal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Date -->
                        <div class="mb-4">
                            <label for="date" class="form-label">
                                Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $milkProduction->date->format('Y-m-d')) }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <!-- Morning Yield -->
                            <div class="col-md-6 mb-4">
                                <label for="morning_yield" class="form-label">Morning Yield (Liters)</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('morning_yield') is-invalid @enderror" 
                                           id="morning_yield" 
                                           name="morning_yield" 
                                           value="{{ old('morning_yield', $milkProduction->morning_yield) }}" 
                                           step="0.01" 
                                           min="0" 
                                           max="100">
                                    <span class="input-group-text">L</span>
                                    @error('morning_yield')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Evening Yield -->
                            <div class="col-md-6 mb-4">
                                <label for="evening_yield" class="form-label">Evening Yield (Liters)</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('evening_yield') is-invalid @enderror" 
                                           id="evening_yield" 
                                           name="evening_yield" 
                                           value="{{ old('evening_yield', $milkProduction->evening_yield) }}" 
                                           step="0.01" 
                                           min="0" 
                                           max="100">
                                    <span class="input-group-text">L</span>
                                    @error('evening_yield')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Lactation Number -->
                            <div class="col-md-6 mb-4">
                                <label for="lactation_number" class="form-label">Lactation Number</label>
                                <input type="number" 
                                       class="form-control @error('lactation_number') is-invalid @enderror" 
                                       id="lactation_number" 
                                       name="lactation_number" 
                                       value="{{ old('lactation_number', $milkProduction->lactation_number) }}" 
                                       min="1" 
                                       max="10">
                                @error('lactation_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Days in Milk -->
                            <div class="col-md-6 mb-4">
                                <label for="days_in_milk" class="form-label">Days in Milk</label>
                                <input type="number" 
                                       class="form-control @error('days_in_milk') is-invalid @enderror" 
                                       id="days_in_milk" 
                                       name="days_in_milk" 
                                       value="{{ old('days_in_milk', $milkProduction->days_in_milk) }}" 
                                       min="1" 
                                       max="400">
                                @error('days_in_milk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3">{{ old('notes', $milkProduction->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Current Total -->
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Record Information:</strong>
                                </div>
                                <div>
                                    Total: <strong>{{ number_format($milkProduction->total_yield, 2) }}</strong> L
                                    | Recorded by: {{ $milkProduction->milker->name ?? 'N/A' }}
                                    | Created: {{ $milkProduction->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('milk-production.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Record
                                </button>
                                <a href="{{ route('milk-production.show', $milkProduction) }}" 
                                   class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-eye me-2"></i>View
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection