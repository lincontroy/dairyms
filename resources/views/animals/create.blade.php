@extends('layouts.app')

@section('title', 'Register New Animal - Dairy Farm')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-plus-circle text-success me-2"></i>Register New Animal
            </h1>
            <p class="mb-0">Add a new animal to the farm registry</p>
        </div>
        <div>
            <a href="{{ route('animals.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Animal Registration Form -->
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-edit me-2"></i>Animal Information
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('animals.store') }}" id="animalForm">
                        @csrf
                        
                        <!-- Basic Information Card -->
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="animal_id" class="form-label required">Animal ID</label>
                                        <input type="text" class="form-control @error('animal_id') is-invalid @enderror" 
                                               id="animal_id" name="animal_id" 
                                               value="{{ old('animal_id') }}" 
                                               placeholder="e.g., COW-001" required>
                                        @error('animal_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Unique identifier for the animal</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="ear_tag" class="form-label required">Ear Tag Number</label>
                                        <input type="text" class="form-control @error('ear_tag') is-invalid @enderror" 
                                               id="ear_tag" name="ear_tag" 
                                               value="{{ old('ear_tag') }}" 
                                               placeholder="e.g., ET-001" required>
                                        @error('ear_tag')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Animal Name (Optional)</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="e.g., Daisy">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="breed" class="form-label required">Breed</label>
                                        <select class="form-select @error('breed') is-invalid @enderror" 
                                                id="breed" name="breed" required>
                                            <option value="">Select Breed</option>
                                            <option value="Holstein Friesian" {{ old('breed') == 'Holstein Friesian' ? 'selected' : '' }}>Holstein Friesian</option>
                                            <option value="Jersey" {{ old('breed') == 'Jersey' ? 'selected' : '' }}>Jersey</option>
                                            <option value="Guernsey" {{ old('breed') == 'Guernsey' ? 'selected' : '' }}>Guernsey</option>
                                            <option value="Ayrshire" {{ old('breed') == 'Ayrshire' ? 'selected' : '' }}>Ayrshire</option>
                                            <option value="Brown Swiss" {{ old('breed') == 'Brown Swiss' ? 'selected' : '' }}>Brown Swiss</option>
                                            <option value="Crossbreed" {{ old('breed') == 'Crossbreed' ? 'selected' : '' }}>Crossbreed</option>
                                            <option value="Other" {{ old('breed') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('breed')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="sex" class="form-label required">Sex</label>
                                        <select class="form-select @error('sex') is-invalid @enderror" 
                                                id="sex" name="sex" required>
                                            <option value="">Select Sex</option>
                                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        </select>
                                        @error('sex')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label required">Date of Birth</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" name="date_of_birth" 
                                               value="{{ old('date_of_birth') }}" 
                                               max="{{ date('Y-m-d') }}" required>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Farm Information Card -->
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-tractor me-2"></i>Farm Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="source" class="form-label required">Source</label>
                                        <select class="form-select @error('source') is-invalid @enderror" 
                                                id="source" name="source" required>
                                            <option value="">Select Source</option>
                                            <option value="born" {{ old('source') == 'born' ? 'selected' : '' }}>Born on Farm</option>
                                            <option value="purchased" {{ old('source') == 'purchased' ? 'selected' : '' }}>Purchased</option>
                                        </select>
                                        @error('source')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label required">Current Status</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="calf" {{ old('status') == 'calf' ? 'selected' : '' }}>Calf</option>
                                            <option value="heifer" {{ old('status') == 'heifer' ? 'selected' : '' }}>Heifer</option>
                                            <option value="lactating" {{ old('status') == 'lactating' ? 'selected' : '' }}>Lactating</option>
                                            <option value="dry" {{ old('status') == 'dry' ? 'selected' : '' }}>Dry</option>
                                            <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                            <option value="dead" {{ old('status') == 'dead' ? 'selected' : '' }}>Dead</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="date_added" class="form-label required">Date Added to Farm</label>
                                        <input type="date" class="form-control @error('date_added') is-invalid @enderror" 
                                               id="date_added" name="date_added" 
                                               value="{{ old('date_added', date('Y-m-d')) }}" 
                                               max="{{ date('Y-m-d') }}" required>
                                        @error('date_added')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="is_active" name="is_active" value="1" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Mark as Active Animal
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Parent Information Card -->
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-users me-2"></i>Parent Information (Optional)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dam_id" class="form-label">Dam (Mother)</label>
                                        <select class="form-select @error('dam_id') is-invalid @enderror" 
                                                id="dam_id" name="dam_id">
                                            <option value="">Select Dam</option>
                                            @foreach($animals->where('sex', 'Female') as $dam)
                                                <option value="{{ $dam->id }}" {{ old('dam_id') == $dam->id ? 'selected' : '' }}>
                                                    {{ $dam->animal_id }} - {{ $dam->name ?? 'Unnamed' }} ({{ $dam->breed }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('dam_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="sire_id" class="form-label">Sire (Father)</label>
                                        <select class="form-select @error('sire_id') is-invalid @enderror" 
                                                id="sire_id" name="sire_id">
                                            <option value="">Select Sire</option>
                                            @foreach($animals->where('sex', 'Male') as $sire)
                                                <option value="{{ $sire->id }}" {{ old('sire_id') == $sire->id ? 'selected' : '' }}>
                                                    {{ $sire->animal_id }} - {{ $sire->name ?? 'Unnamed' }} ({{ $sire->breed }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sire_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes Card -->
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-sticky-note me-2"></i>Additional Notes
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="4" 
                                              placeholder="Enter any additional information about the animal...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Register Animal
                            </button>
                            <a href="{{ route('animals.index') }}" class="btn btn-secondary btn-lg px-5 ms-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Form Help Text -->
            <div class="alert alert-info mt-3">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading">Registration Tips</h6>
                        <ul class="mb-0">
                            <li>Animal ID and Ear Tag must be unique</li>
                            <li>Date of birth cannot be in the future</li>
                            <li>Status determines animal's current condition</li>
                            <li>Parent information helps track lineage</li>
                        </ul>
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
        // Set max date for date inputs
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_of_birth').max = today;
        document.getElementById('date_added').max = today;
        
        // Auto-generate animal ID if empty
        document.getElementById('animal_id').addEventListener('blur', function() {
            if (!this.value.trim()) {
                const sex = document.getElementById('sex').value;
                const breed = document.getElementById('breed').value;
                if (sex && breed) {
                    const prefix = sex === 'Female' ? 'COW' : 'BULL';
                    const breedCode = breed.substring(0, 3).toUpperCase();
                    this.value = `${prefix}-${breedCode}-${Math.floor(1000 + Math.random() * 9000)}`;
                }
            }
        });
        
        // Auto-generate ear tag if empty
        document.getElementById('ear_tag').addEventListener('blur', function() {
            if (!this.value.trim()) {
                const animalId = document.getElementById('animal_id').value;
                if (animalId) {
                    this.value = `ET-${animalId.substring(animalId.length - 4)}`;
                }
            }
        });
        
        // Form validation
        document.getElementById('animalForm').addEventListener('submit', function(e) {
            const dateOfBirth = new Date(document.getElementById('date_of_birth').value);
            const dateAdded = new Date(document.getElementById('date_added').value);
            
            if (dateOfBirth > dateAdded) {
                e.preventDefault();
                alert('Date of birth cannot be after date added to farm!');
                document.getElementById('date_of_birth').focus();
            }
        });
    });
</script>
@endpush