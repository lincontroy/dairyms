@extends('layouts.app')

@section('title', 'Add Health Record - Dairy Farm Management')
@section('page-title', 'Add Health Record')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('health-records.index') }}">Health Records</a>
    </li>
    <li class="breadcrumb-item active">Add Record</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heartbeat me-2 text-danger"></i>
                        Add Health Record
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('health-records.store') }}">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-danger mb-3 border-bottom pb-2">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
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
                                                {{ old('animal_id', $selectedAnimal?->id) == $animal->id ? 'selected' : '' }}>
                                            {{ $animal->animal_id }} - {{ $animal->name ?? 'Unnamed' }} 
                                            ({{ $animal->breed }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('animal_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('date') is-invalid @enderror" 
                                       id="date" 
                                       name="date" 
                                       value="{{ old('date', today()->format('Y-m-d')) }}" 
                                       required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Diagnosis & Symptoms -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-danger mb-3 border-bottom pb-2">
                                    <i class="fas fa-stethoscope me-2"></i>Diagnosis & Symptoms
                                </h6>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="diagnosis" class="form-label">
                                    Diagnosis <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('diagnosis') is-invalid @enderror" 
                                       id="diagnosis" 
                                       name="diagnosis" 
                                       value="{{ old('diagnosis') }}" 
                                       required
                                       placeholder="e.g., Mastitis, Foot Rot, Pneumonia">
                                @error('diagnosis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="clinical_signs" class="form-label">Clinical Signs/Observations</label>
                                <textarea class="form-control @error('clinical_signs') is-invalid @enderror" 
                                          id="clinical_signs" 
                                          name="clinical_signs" 
                                          rows="3"
                                          placeholder="Describe symptoms, behavior changes, physical signs...">{{ old('clinical_signs') }}</textarea>
                                @error('clinical_signs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Treatment Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-danger mb-3 border-bottom pb-2">
                                    <i class="fas fa-pills me-2"></i>Treatment Information
                                </h6>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="treatment" class="form-label">
                                    Treatment <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('treatment') is-invalid @enderror" 
                                          id="treatment" 
                                          name="treatment" 
                                          rows="3"
                                          required
                                          placeholder="Describe the treatment plan...">{{ old('treatment') }}</textarea>
                                @error('treatment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="drug_name" class="form-label">Drug Name</label>
                                <input type="text" 
                                       class="form-control @error('drug_name') is-invalid @enderror" 
                                       id="drug_name" 
                                       name="drug_name" 
                                       value="{{ old('drug_name') }}"
                                       placeholder="e.g., Penicillin, Oxytetracycline">
                                @error('drug_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="dosage" class="form-label">Dosage</label>
                                <input type="text" 
                                       class="form-control @error('dosage') is-invalid @enderror" 
                                       id="dosage" 
                                       name="dosage" 
                                       value="{{ old('dosage') }}"
                                       placeholder="e.g., 10mg/kg">
                                @error('dosage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="route" class="form-label">Route</label>
                                <select class="form-select @error('route') is-invalid @enderror" 
                                        id="route" 
                                        name="route">
                                    <option value="">Select Route</option>
                                    <option value="IM" {{ old('route') == 'IM' ? 'selected' : '' }}>IM (Intramuscular)</option>
                                    <option value="IV" {{ old('route') == 'IV' ? 'selected' : '' }}>IV (Intravenous)</option>
                                    <option value="SC" {{ old('route') == 'SC' ? 'selected' : '' }}>SC (Subcutaneous)</option>
                                    <option value="Oral" {{ old('route') == 'Oral' ? 'selected' : '' }}>Oral</option>
                                    <option value="Topical" {{ old('route') == 'Topical' ? 'selected' : '' }}>Topical</option>
                                </select>
                                @error('route')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="text" 
                                       class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" 
                                       name="duration" 
                                       value="{{ old('duration') }}"
                                       placeholder="e.g., 5 days, 1 week">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Withdrawal Periods & Veterinarian -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-danger mb-3 border-bottom pb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Withdrawal Periods & Veterinarian
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="milk_withdrawal_days" class="form-label">Milk Withdrawal (Days)</label>
                                <input type="number" 
                                       class="form-control @error('milk_withdrawal_days') is-invalid @enderror" 
                                       id="milk_withdrawal_days" 
                                       name="milk_withdrawal_days" 
                                       value="{{ old('milk_withdrawal_days') }}"
                                       min="0" 
                                       max="365"
                                       placeholder="0">
                                @error('milk_withdrawal_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Days to withhold milk after treatment</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="meat_withdrawal_days" class="form-label">Meat Withdrawal (Days)</label>
                                <input type="number" 
                                       class="form-control @error('meat_withdrawal_days') is-invalid @enderror" 
                                       id="meat_withdrawal_days" 
                                       name="meat_withdrawal_days" 
                                       value="{{ old('meat_withdrawal_days') }}"
                                       min="0" 
                                       max="365"
                                       placeholder="0">
                                @error('meat_withdrawal_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Days to withhold meat after treatment</div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="veterinarian" class="form-label">Veterinarian</label>
                                <input type="text" 
                                       class="form-control @error('veterinarian') is-invalid @enderror" 
                                       id="veterinarian" 
                                       name="veterinarian" 
                                       value="{{ old('veterinarian') }}"
                                       placeholder="Name of veterinarian or technician">
                                @error('veterinarian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Outcome & Notes -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-danger mb-3 border-bottom pb-2">
                                    <i class="fas fa-clipboard-check me-2"></i>Outcome & Notes
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="outcome" class="form-label">
                                    Outcome <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('outcome') is-invalid @enderror" 
                                        id="outcome" 
                                        name="outcome" 
                                        required>
                                    <option value="">Select Outcome</option>
                                    <option value="Recovered" {{ old('outcome') == 'Recovered' ? 'selected' : '' }}>Recovered</option>
                                    <option value="Under Treatment" {{ old('outcome') == 'Under Treatment' ? 'selected' : '' }}>Under Treatment</option>
                                    <option value="Not Responding" {{ old('outcome') == 'Not Responding' ? 'selected' : '' }}>Not Responding</option>
                                    <option value="Died" {{ old('outcome') == 'Died' ? 'selected' : '' }}>Died</option>
                                </select>
                                @error('outcome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3"
                                          placeholder="Any additional observations or follow-up instructions...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('health-records.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Save Health Record
                            </button>
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
        // Auto-fill today's date if not set
        const dateField = document.getElementById('date');
        if (!dateField.value) {
            dateField.value = new Date().toISOString().split('T')[0];
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const outcome = document.getElementById('outcome').value;
            if (!outcome) {
                e.preventDefault();
                alert('Please select an outcome for this health record.');
                document.getElementById('outcome').focus();
                return false;
            }
            return true;
        });
    });
</script>
@endpush

@push('styles')
<style>
    .form-select:focus, .form-control:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    
    h6.text-danger {
        color: #dc3545 !important;
    }
</style>
@endpush
@endsection