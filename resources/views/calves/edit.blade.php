@extends('layouts.app')

@section('title', 'Edit Calf: ' . $calf->calf_id . ' - Dairy Farm')
@section('page-title', 'Edit Calf: ' . $calf->calf_id)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('calves.index') }}">Calves</a></li>
<li class="breadcrumb-item"><a href="{{ route('calves.show', $calf) }}">{{ $calf->calf_id }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Edit Calf: {{ $calf->calf_id }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('calves.update', $calf) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Parent Information -->
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-users me-2"></i> Parent Information
                                </h6>
                            </div>

                            <!-- Dam (Mother) -->
                            <div class="col-md-6">
                                <label for="dam_id" class="form-label">Dam (Mother) *</label>
                                <select class="form-select @error('dam_id') is-invalid @enderror" 
                                        id="dam_id" 
                                        name="dam_id"
                                        required>
                                    <option value="">Select Dam</option>
                                    @foreach($dams as $dam)
                                    <option value="{{ $dam->id }}" 
                                        {{ old('dam_id', $calf->dam_id) == $dam->id ? 'selected' : '' }}>
                                        {{ $dam->name }} ({{ $dam->ear_tag }}) - {{ ucfirst($dam->status) }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Select the mother cow
                                </small>
                                @error('dam_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sire (Father) -->
                            <div class="col-md-6">
                                <label for="sire_id" class="form-label">Sire (Father) (Optional)</label>
                                <select class="form-select @error('sire_id') is-invalid @enderror" 
                                        id="sire_id" 
                                        name="sire_id">
                                    <option value="">Select Sire</option>
                                    @foreach($sires as $sire)
                                    <option value="{{ $sire->id }}" 
                                        {{ old('sire_id', $calf->sire_id) == $sire->id ? 'selected' : '' }}>
                                        {{ $sire->name }} ({{ $sire->ear_tag }})
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Select the father bull (if known)
                                </small>
                                @error('sire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Breeding Record Link -->
                            <div class="col-12">
                                <label for="breeding_record_id" class="form-label">Related Breeding Record (Optional)</label>
                                <select class="form-select @error('breeding_record_id') is-invalid @enderror" 
                                        id="breeding_record_id" 
                                        name="breeding_record_id">
                                    <option value="">No Breeding Record</option>
                                    @foreach($breedingRecords as $record)
                                    <option value="{{ $record->id }}" 
                                        {{ old('breeding_record_id', $calf->breeding_record_id) == $record->id ? 'selected' : '' }}>
                                        Record #{{ $record->id }} - {{ $record->animal->name ?? 'Unknown' }} 
                                        (Bred: {{ $record->date_of_service?->format('M d, Y') ?? 'N/A' }})
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Link to existing breeding record if available
                                </small>
                                @error('breeding_record_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Calf Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i> Calf Information
                                </h6>
                            </div>

                            <!-- Calf ID -->
                            <div class="col-md-6">
                                <label for="calf_id" class="form-label">Calf ID</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="calf_id_display" 
                                       value="{{ $calf->calf_id }}"
                                       disabled readonly>
                                <small class="form-text text-muted">
                                    Calf ID is automatically generated and cannot be changed
                                </small>
                            </div>

                            <!-- Ear Tag -->
                            <div class="col-md-6">
                                <label for="ear_tag" class="form-label">Ear Tag *</label>
                                <input type="text" 
                                       class="form-control @error('ear_tag') is-invalid @enderror" 
                                       id="ear_tag" 
                                       name="ear_tag" 
                                       value="{{ old('ear_tag', $calf->ear_tag) }}"
                                       placeholder="e.g., C001, CT-2024-001"
                                       required>
                                <small class="form-text text-muted">
                                    Unique identification tag for the calf
                                </small>
                                @error('ear_tag')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name (Optional)</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $calf->name) }}"
                                       placeholder="e.g., Daisy, Brownie">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status"
                                        required>
                                    <option value="alive" {{ old('status', $calf->status) == 'alive' ? 'selected' : '' }}>Alive</option>
                                    <option value="dead" {{ old('status', $calf->status) == 'dead' ? 'selected' : '' }}>Dead</option>
                                    <option value="sold" {{ old('status', $calf->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                    <option value="transferred" {{ old('status', $calf->status) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sex -->
                            <div class="col-md-4">
                                <label for="sex" class="form-label">Sex *</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="sex" 
                                               id="sex_male" 
                                               value="male"
                                               {{ old('sex', $calf->sex) == 'male' ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="sex_male">
                                            Male (Bull Calf)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="sex" 
                                               id="sex_female" 
                                               value="female"
                                               {{ old('sex', $calf->sex) == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sex_female">
                                            Female (Heifer)
                                        </label>
                                    </div>
                                </div>
                                @error('sex')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-4">
                                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                <input type="date" 
                                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $calf->date_of_birth->format('Y-m-d')) }}"
                                       required>
                                @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Birth Weight -->
                            <div class="col-md-4">
                                <label for="birth_weight" class="form-label">Birth Weight (kg) (Optional)</label>
                                <div class="input-group">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="100"
                                           class="form-control @error('birth_weight') is-invalid @enderror" 
                                           id="birth_weight" 
                                           name="birth_weight" 
                                           value="{{ old('birth_weight', $calf->birth_weight) }}"
                                           placeholder="e.g., 35.5">
                                    <span class="input-group-text">kg</span>
                                </div>
                                @error('birth_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Birth Details -->
                            <div class="col-md-4">
                                <label for="birth_type" class="form-label">Birth Type *</label>
                                <select class="form-select @error('birth_type') is-invalid @enderror" 
                                        id="birth_type" 
                                        name="birth_type"
                                        required>
                                    <option value="single" {{ old('birth_type', $calf->birth_type) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="twin" {{ old('birth_type', $calf->birth_type) == 'twin' ? 'selected' : '' }}>Twin</option>
                                    <option value="triplet" {{ old('birth_type', $calf->birth_type) == 'triplet' ? 'selected' : '' }}>Triplet</option>
                                </select>
                                @error('birth_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="delivery_type" class="form-label">Delivery Type *</label>
                                <select class="form-select @error('delivery_type') is-invalid @enderror" 
                                        id="delivery_type" 
                                        name="delivery_type"
                                        required>
                                    <option value="normal" {{ old('delivery_type', $calf->delivery_type) == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="assisted" {{ old('delivery_type', $calf->delivery_type) == 'assisted' ? 'selected' : '' }}>Assisted</option>
                                    <option value="caesarean" {{ old('delivery_type', $calf->delivery_type) == 'caesarean' ? 'selected' : '' }}>Caesarean</option>
                                </select>
                                @error('delivery_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="vaccination_status" class="form-label">Vaccination Status *</label>
                                <select class="form-select @error('vaccination_status') is-invalid @enderror" 
                                        id="vaccination_status" 
                                        name="vaccination_status"
                                        required>
                                    <option value="pending" {{ old('vaccination_status', $calf->vaccination_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="partial" {{ old('vaccination_status', $calf->vaccination_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="complete" {{ old('vaccination_status', $calf->vaccination_status) == 'complete' ? 'selected' : '' }}>Complete</option>
                                </select>
                                @error('vaccination_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Health Information -->
                            <div class="col-md-6">
                                <label for="health_status" class="form-label">Health Status *</label>
                                <select class="form-select @error('health_status') is-invalid @enderror" 
                                        id="health_status" 
                                        name="health_status"
                                        required>
                                    <option value="excellent" {{ old('health_status', $calf->health_status) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="good" {{ old('health_status', $calf->health_status) == 'good' ? 'selected' : '' }}>Good</option>
                                    <option value="fair" {{ old('health_status', $calf->health_status) == 'fair' ? 'selected' : '' }}>Fair</option>
                                    <option value="poor" {{ old('health_status', $calf->health_status) == 'poor' ? 'selected' : '' }}>Poor</option>
                                </select>
                                @error('health_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="color_markings" class="form-label">Color & Markings (Optional)</label>
                                <input type="text" 
                                       class="form-control @error('color_markings') is-invalid @enderror" 
                                       id="color_markings" 
                                       name="color_markings" 
                                       value="{{ old('color_markings', $calf->color_markings) }}"
                                       placeholder="e.g., Brown with white spots">
                                @error('color_markings')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Weaning Information -->
                            <div class="col-md-6">
                                <label for="weaning_date" class="form-label">Weaning Date (Optional)</label>
                                <input type="date" 
                                       class="form-control @error('weaning_date') is-invalid @enderror" 
                                       id="weaning_date" 
                                       name="weaning_date" 
                                       value="{{ old('weaning_date', optional($calf->weaning_date)->format('Y-m-d')) }}">
                                <small class="form-text text-muted">
                                    Expected or actual weaning date
                                </small>
                                @error('weaning_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="weaning_weight" class="form-label">Weaning Weight (kg) (Optional)</label>
                                <div class="input-group">
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="500"
                                           class="form-control @error('weaning_weight') is-invalid @enderror" 
                                           id="weaning_weight" 
                                           name="weaning_weight" 
                                           value="{{ old('weaning_weight', $calf->weaning_weight) }}"
                                           placeholder="e.g., 150.5">
                                    <span class="input-group-text">kg</span>
                                </div>
                                @error('weaning_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Checkboxes -->
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_weaned" 
                                           name="is_weaned"
                                           value="1"
                                           {{ old('is_weaned', $calf->is_weaned) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_weaned">
                                        Calf is weaned
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="requires_special_care" 
                                           name="requires_special_care"
                                           value="1"
                                           {{ old('requires_special_care', $calf->requires_special_care) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requires_special_care">
                                        Requires special care
                                    </label>
                                </div>
                            </div>

                            <!-- Special Care Notes (Conditional) -->
                            <div class="col-12" id="specialCareNotes" 
                                 style="{{ $calf->requires_special_care ? '' : 'display: none;' }}">
                                <label for="special_care_notes" class="form-label">Special Care Notes</label>
                                <textarea class="form-control @error('special_care_notes') is-invalid @enderror" 
                                          id="special_care_notes" 
                                          name="special_care_notes" 
                                          rows="2"
                                          placeholder="Describe the special care requirements...">{{ old('special_care_notes', $calf->special_care_notes) }}</textarea>
                                @error('special_care_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- General Notes -->
                            <div class="col-12">
                                <label for="notes" class="form-label">General Notes (Optional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3"
                                          placeholder="Any additional observations or notes...">{{ old('notes', $calf->notes) }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('calves.show', $calf) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Update Calf
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Calf Details Card -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Calf Information Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <strong>Calf ID:</strong><br>
                                {{ $calf->calf_id }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <strong>Recorded By:</strong><br>
                                {{ $calf->recordedBy->name ?? 'Unknown' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <strong>Date Recorded:</strong><br>
                                {{ $calf->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Note:</strong> Changing the dam or breeding record will update related records. 
                        Changes to birth type may affect breeding record statistics.
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
    // Toggle special care notes
    const specialCareCheckbox = document.getElementById('requires_special_care');
    const specialCareNotes = document.getElementById('specialCareNotes');
    
    specialCareCheckbox.addEventListener('change', function() {
        if (this.checked) {
            specialCareNotes.style.display = 'block';
        } else {
            specialCareNotes.style.display = 'none';
        }
    });
    
    // Format birth weight on blur
    const birthWeightInput = document.getElementById('birth_weight');
    if (birthWeightInput) {
        birthWeightInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }
    
    // Format weaning weight on blur
    const weaningWeightInput = document.getElementById('weaning_weight');
    if (weaningWeightInput) {
        weaningWeightInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }
    
    // Set max date for date inputs
    const today = new Date().toISOString().split('T')[0];
    const dateOfBirth = document.getElementById('date_of_birth');
    const weaningDate = document.getElementById('weaning_date');
    
    if (dateOfBirth) {
        dateOfBirth.max = today;
    }
    
    if (weaningDate) {
        weaningDate.max = today;
    }
});
</script>
@endpush