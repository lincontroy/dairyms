@extends('layouts.app')

@section('title', 'Edit Breeding Record - Dairy Farm Management')
@section('page-title', 'Edit Breeding Record')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('breeding-records.index') }}">Breeding Records</a>
    </li>
    <li class="breadcrumb-item active">Edit Record</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2 text-warning"></i>
                        Edit Breeding Record
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('breeding-records.update', $breedingRecord) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Animal Selection -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-warning mb-3 border-bottom pb-2">
                                    <i class="fas fa-cow me-2"></i>Animal Information
                                </h6>
                            </div>
                            
                            <div class="col-md-12 mb-3">
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
                                                {{ old('animal_id', $breedingRecord->animal_id) == $animal->id ? 'selected' : '' }}>
                                            {{ $animal->animal_id }} - {{ $animal->name ?? 'Unnamed' }} 
                                            ({{ $animal->breed }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('animal_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Breeding Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-warning mb-3 border-bottom pb-2">
                                    <i class="fas fa-bullhorn me-2"></i>Breeding Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_service" class="form-label">
                                    Date of Service <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('date_of_service') is-invalid @enderror" 
                                       id="date_of_service" 
                                       name="date_of_service" 
                                       value="{{ old('date_of_service', $breedingRecord->date_of_service->format('Y-m-d')) }}" 
                                       required
                                       onchange="calculateExpectedDate()">
                                @error('date_of_service')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="breeding_method" class="form-label">
                                    Breeding Method <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('breeding_method') is-invalid @enderror" 
                                        id="breeding_method" 
                                        name="breeding_method" 
                                        required>
                                    <option value="">Select Method</option>
                                    <option value="Natural" {{ old('breeding_method', $breedingRecord->breeding_method) == 'Natural' ? 'selected' : '' }}>Natural Service</option>
                                    <option value="AI" {{ old('breeding_method', $breedingRecord->breeding_method) == 'AI' ? 'selected' : '' }}>Artificial Insemination (AI)</option>
                                    <option value="Synchronization" {{ old('breeding_method', $breedingRecord->breeding_method) == 'Synchronization' ? 'selected' : '' }}>Synchronization Program</option>
                                </select>
                                @error('breeding_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="bull_semen_id" class="form-label">Bull/Semen ID</label>
                                <input type="text" 
                                       class="form-control @error('bull_semen_id') is-invalid @enderror" 
                                       id="bull_semen_id" 
                                       name="bull_semen_id" 
                                       value="{{ old('bull_semen_id', $breedingRecord->bull_semen_id) }}"
                                       placeholder="e.g., BULL-001, SEMEN-ABC123">
                                @error('bull_semen_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="technician" class="form-label">Technician</label>
                                <input type="text" 
                                       class="form-control @error('technician') is-invalid @enderror" 
                                       id="technician" 
                                       name="technician" 
                                       value="{{ old('technician', $breedingRecord->technician) }}"
                                       placeholder="Name of technician">
                                @error('technician')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pregnancy Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-warning mb-3 border-bottom pb-2">
                                    <i class="fas fa-baby me-2"></i>Pregnancy Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pregnancy_diagnosis_date" class="form-label">Pregnancy Diagnosis Date</label>
                                <input type="date" 
                                       class="form-control @error('pregnancy_diagnosis_date') is-invalid @enderror" 
                                       id="pregnancy_diagnosis_date" 
                                       name="pregnancy_diagnosis_date" 
                                       value="{{ old('pregnancy_diagnosis_date', optional($breedingRecord->pregnancy_diagnosis_date)->format('Y-m-d')) }}">
                                @error('pregnancy_diagnosis_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pregnancy_result" class="form-label">Pregnancy Result</label>
                                <select class="form-select @error('pregnancy_result') is-invalid @enderror" 
                                        id="pregnancy_result" 
                                        name="pregnancy_result">
                                    <option value="">Pending/Not Yet Checked</option>
                                    <option value="1" {{ old('pregnancy_result', $breedingRecord->pregnancy_result) == '1' ? 'selected' : '' }}>Pregnant</option>
                                    <option value="0" {{ old('pregnancy_result', $breedingRecord->pregnancy_result) == '0' ? 'selected' : '' }}>Not Pregnant</option>
                                </select>
                                @error('pregnancy_result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="expected_calving_date" class="form-label">Expected Calving Date</label>
                                <input type="date" 
                                       class="form-control @error('expected_calving_date') is-invalid @enderror" 
                                       id="expected_calving_date" 
                                       name="expected_calving_date" 
                                       value="{{ old('expected_calving_date', optional($breedingRecord->expected_calving_date)->format('Y-m-d')) }}">
                                @error('expected_calving_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Auto-calculated: ~283 days from service</div>
                            </div>
                        </div>

                        <!-- Calving Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-warning mb-3 border-bottom pb-2">
                                    <i class="fas fa-calendar-check me-2"></i>Calving Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="actual_calving_date" class="form-label">Actual Calving Date</label>
                                <input type="date" 
                                       class="form-control @error('actual_calving_date') is-invalid @enderror" 
                                       id="actual_calving_date" 
                                       name="actual_calving_date" 
                                       value="{{ old('actual_calving_date', optional($breedingRecord->actual_calving_date)->format('Y-m-d')) }}">
                                @error('actual_calving_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="calving_outcome" class="form-label">Calving Outcome</label>
                                <select class="form-select @error('calving_outcome') is-invalid @enderror" 
                                        id="calving_outcome" 
                                        name="calving_outcome">
                                    <option value="">Select Outcome</option>
                                    <option value="Live Calf" {{ old('calving_outcome', $breedingRecord->calving_outcome) == 'Live Calf' ? 'selected' : '' }}>Live Calf</option>
                                    <option value="Stillborn" {{ old('calving_outcome', $breedingRecord->calving_outcome) == 'Stillborn' ? 'selected' : '' }}>Stillborn</option>
                                    <option value="Twins" {{ old('calving_outcome', $breedingRecord->calving_outcome) == 'Twins' ? 'selected' : '' }}>Twins</option>
                                    <option value="Abortion" {{ old('calving_outcome', $breedingRecord->calving_outcome) == 'Abortion' ? 'selected' : '' }}>Abortion</option>
                                    <option value="Retained Placenta" {{ old('calving_outcome', $breedingRecord->calving_outcome) == 'Retained Placenta' ? 'selected' : '' }}>Retained Placenta</option>
                                    <option value="Dystocia" {{ old('calving_outcome', $breedingRecord->calving_outcome) == 'Dystocia' ? 'selected' : '' }}>Dystocia (Difficult Birth)</option>
                                </select>
                                @error('calving_outcome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="text-warning mb-3 border-bottom pb-2">
                                    <i class="fas fa-sticky-note me-2"></i>Additional Information
                                </h6>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3">{{ old('notes', $breedingRecord->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Auto-calculation preview -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-calculator me-2"></i>
                                    <strong>Pregnancy Timeline:</strong>
                                </div>
                                <div>
                                    <span id="pregnancyTimeline">
                                        @php
                                            if ($breedingRecord->date_of_service) {
                                                $serviceDate = $breedingRecord->date_of_service;
                                                $expectedDate = $breedingRecord->expected_calving_date ?? $serviceDate->copy()->addDays(283);
                                                $now = now();
                                                
                                                $daysSinceService = $now->diffInDays($serviceDate);
                                                $daysToCalving = $expectedDate ? $expectedDate->diffInDays($now, false) : null;
                                                
                                                if ($daysSinceService < 0) {
                                                    echo 'Future breeding date';
                                                } elseif ($daysSinceService <= 28) {
                                                    echo 'Early pregnancy (' . $daysSinceService . ' days since service)';
                                                } elseif ($daysSinceService <= 60) {
                                                    echo 'Pregnancy confirmed (' . $daysSinceService . ' days)';
                                                } elseif ($daysToCalving > 0) {
                                                    echo 'Mid-pregnancy - ' . $daysToCalving . ' days to calving';
                                                } else {
                                                    echo 'Past expected calving date';
                                                }
                                            } else {
                                                echo 'Enter date of service to see timeline';
                                            }
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Record Information -->
                        <div class="alert alert-secondary mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>Created:</strong> {{ $breedingRecord->created_at->format('M d, Y H:i') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Last Updated:</strong> {{ $breedingRecord->updated_at->format('M d, Y H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('breeding-records.show', $breedingRecord) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Update Record
                                </button>
                                <a href="{{ route('breeding-records.show', $breedingRecord) }}" 
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate expected calving date (283 days)
        function calculateExpectedDate() {
            const dateOfService = document.getElementById('date_of_service').value;
            if (dateOfService) {
                const serviceDate = new Date(dateOfService);
                const expectedDate = new Date(serviceDate);
                expectedDate.setDate(serviceDate.getDate() + 283);
                
                // Format as YYYY-MM-DD
                const formattedDate = expectedDate.toISOString().split('T')[0];
                document.getElementById('expected_calving_date').value = formattedDate;
                
                // Update timeline preview
                updatePregnancyTimeline(serviceDate, expectedDate);
            }
        }
        
        // Update pregnancy timeline display
        function updatePregnancyTimeline(serviceDate, expectedDate) {
            const now = new Date();
            const serviceDateObj = new Date(serviceDate);
            const expectedDateObj = new Date(expectedDate);
            
            const daysSinceService = Math.floor((now - serviceDateObj) / (1000 * 60 * 60 * 24));
            const daysToCalving = Math.floor((expectedDateObj - now) / (1000 * 60 * 60 * 24));
            
            let timelineText = '';
            
            if (daysSinceService < 0) {
                timelineText = 'Future breeding date';
            } else if (daysSinceService <= 28) {
                timelineText = `Early pregnancy (${daysSinceService} days since service)`;
            } else if (daysSinceService <= 60) {
                timelineText = `Pregnancy confirmed (${daysSinceService} days)`;
            } else if (daysToCalving > 0) {
                timelineText = `Mid-pregnancy - ${daysToCalving} days to calving`;
            } else {
                timelineText = 'Past expected calving date';
            }
            
            document.getElementById('pregnancyTimeline').textContent = timelineText;
        }
        
        // Event listeners
        document.getElementById('date_of_service').addEventListener('change', calculateExpectedDate);
        
        // Initialize calculation on page load
        const dateOfServiceField = document.getElementById('date_of_service');
        if (dateOfServiceField.value) {
            calculateExpectedDate();
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const actualCalvingDate = document.getElementById('actual_calving_date').value;
            const calvingOutcome = document.getElementById('calving_outcome').value;
            
            if (actualCalvingDate && !calvingOutcome) {
                e.preventDefault();
                alert('Please select a calving outcome if you entered an actual calving date.');
                document.getElementById('calving_outcome').focus();
                return false;
            }
            
            const pregnancyResult = document.getElementById('pregnancy_result').value;
            const pregnancyDate = document.getElementById('pregnancy_diagnosis_date').value;
            
            if (pregnancyResult && !pregnancyDate) {
                if (confirm('You selected a pregnancy result but no diagnosis date. Would you like to set the diagnosis date to today?')) {
                    e.preventDefault();
                    const today = new Date().toISOString().split('T')[0];
                    document.getElementById('pregnancy_diagnosis_date').value = today;
                    form.submit();
                    return false;
                }
            }
            
            return true;
        });
        
        // Auto-calculate expected date if pregnancy is confirmed but no expected date
        const pregnancyResultField = document.getElementById('pregnancy_result');
        const expectedDateField = document.getElementById('expected_calving_date');
        
        pregnancyResultField.addEventListener('change', function() {
            if (this.value === '1' && !expectedDateField.value) {
                calculateExpectedDate();
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .form-select:focus, .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    }
    
    h6.text-warning {
        color: #ffc107 !important;
    }
    
    .alert-info {
        background-color: #cff4fc;
        border-color: #b6effb;
    }
    
    .alert-secondary {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
</style>
@endpush
@endsection