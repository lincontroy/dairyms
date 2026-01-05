@extends('layouts.app')

@section('title', 'Edit Animal - Dairy Farm Management')
@section('page-title', 'Edit Animal')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('animals.index') }}">Animals</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('animals.show', $animal) }}">{{ $animal->name ?? $animal->animal_id }}</a>
    </li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2 text-primary"></i>
                        Edit Animal: {{ $animal->name ?? $animal->animal_id }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('animals.update', $animal) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Column: Basic Information -->
                            <div class="col-md-6">
                                <h6 class="text-success mb-3 border-bottom pb-2">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h6>

                                <!-- Animal ID -->
                                <div class="mb-3">
                                    <label for="animal_id" class="form-label">
                                        Animal ID <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('animal_id') is-invalid @enderror" 
                                           id="animal_id" 
                                           name="animal_id" 
                                           value="{{ old('animal_id', $animal->animal_id) }}" 
                                           required>
                                    @error('animal_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Unique identifier for the animal</div>
                                </div>

                                <!-- Ear Tag -->
                                <div class="mb-3">
                                    <label for="ear_tag" class="form-label">
                                        Ear Tag Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('ear_tag') is-invalid @enderror" 
                                           id="ear_tag" 
                                           name="ear_tag" 
                                           value="{{ old('ear_tag', $animal->ear_tag) }}" 
                                           required>
                                    @error('ear_tag')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $animal->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional name for the animal</div>
                                </div>

                                <!-- Breed -->
                                <div class="mb-3">
                                    <label for="breed" class="form-label">
                                        Breed <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('breed') is-invalid @enderror" 
                                            id="breed" 
                                            name="breed" 
                                            required>
                                        <option value="">Select Breed</option>
                                        <option value="Friesian" {{ old('breed', $animal->breed) == 'Friesian' ? 'selected' : '' }}>Friesian</option>
                                        <option value="Jersey" {{ old('breed', $animal->breed) == 'Jersey' ? 'selected' : '' }}>Jersey</option>
                                        <option value="Ayrshire" {{ old('breed', $animal->breed) == 'Ayrshire' ? 'selected' : '' }}>Ayrshire</option>
                                        <option value="Guernsey" {{ old('breed', $animal->breed) == 'Guernsey' ? 'selected' : '' }}>Guernsey</option>
                                        <option value="Crossbreed" {{ old('breed', $animal->breed) == 'Crossbreed' ? 'selected' : '' }}>Crossbreed</option>
                                        <option value="Indigenous" {{ old('breed', $animal->breed) == 'Indigenous' ? 'selected' : '' }}>Indigenous</option>
                                        <option value="Other" {{ old('breed', $animal->breed) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('breed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">
                                        Date of Birth <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" 
                                           name="date_of_birth" 
                                           value="{{ old('date_of_birth', $animal->date_of_birth->format('Y-m-d')) }}" 
                                           required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sex -->
                                <div class="mb-3">
                                    <label for="sex" class="form-label">
                                        Sex <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('sex') is-invalid @enderror" 
                                            id="sex" 
                                            name="sex" 
                                            required>
                                        <option value="">Select Sex</option>
                                        <option value="Female" {{ old('sex', $animal->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Male" {{ old('sex', $animal->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                    </select>
                                    @error('sex')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Photo Upload -->
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Animal Photo</label>
                                    @if($animal->photo)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($animal->photo) }}" 
                                                 alt="Current Photo" 
                                                 class="img-thumbnail mb-2"
                                                 style="max-width: 200px; max-height: 200px;">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="remove_photo" 
                                                       name="remove_photo" 
                                                       value="1">
                                                <label class="form-check-label text-danger" for="remove_photo">
                                                    Remove current photo
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" 
                                           class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" 
                                           name="photo" 
                                           accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Max file size: 2MB. Allowed: jpg, png, gif</div>
                                </div>
                            </div>

                            <!-- Right Column: Additional Information -->
                            <div class="col-md-6">
                                <h6 class="text-success mb-3 border-bottom pb-2">
                                    <i class="fas fa-cog me-2"></i>Status & Details
                                </h6>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required
                                            onchange="toggleStatusFields()">
                                        <option value="">Select Status</option>
                                        <option value="calf" {{ old('status', $animal->status) == 'calf' ? 'selected' : '' }}>Calf</option>
                                        <option value="heifer" {{ old('status', $animal->status) == 'heifer' ? 'selected' : '' }}>Heifer</option>
                                        <option value="lactating" {{ old('status', $animal->status) == 'lactating' ? 'selected' : '' }}>Lactating</option>
                                        <option value="dry" {{ old('status', $animal->status) == 'dry' ? 'selected' : '' }}>Dry</option>
                                        <option value="sold" {{ old('status', $animal->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                        <option value="dead" {{ old('status', $animal->status) == 'dead' ? 'selected' : '' }}>Dead</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status-specific fields (initially hidden) -->
                                <div id="soldFields" class="mb-3" style="display: {{ old('status', $animal->status) == 'sold' ? 'block' : 'none' }};">
                                    <label for="date_sold" class="form-label">Date Sold</label>
                                    <input type="date" 
                                           class="form-control @error('date_sold') is-invalid @enderror" 
                                           id="date_sold" 
                                           name="date_sold" 
                                           value="{{ old('date_sold', optional($animal->date_sold)->format('Y-m-d')) }}">
                                    @error('date_sold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label for="sale_price" class="form-label mt-2">Sale Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('sale_price') is-invalid @enderror" 
                                               id="sale_price" 
                                               name="sale_price" 
                                               value="{{ old('sale_price', $animal->sale_price) }}" 
                                               step="0.01" 
                                               min="0">
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="deadFields" class="mb-3" style="display: {{ old('status', $animal->status) == 'dead' ? 'block' : 'none' }};">
                                    <label for="date_died" class="form-label">Date of Death</label>
                                    <input type="date" 
                                           class="form-control @error('date_died') is-invalid @enderror" 
                                           id="date_died" 
                                           name="date_died" 
                                           value="{{ old('date_died', optional($animal->date_died)->format('Y-m-d')) }}">
                                    @error('date_died')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label for="death_cause" class="form-label mt-2">Cause of Death</label>
                                    <input type="text" 
                                           class="form-control @error('death_cause') is-invalid @enderror" 
                                           id="death_cause" 
                                           name="death_cause" 
                                           value="{{ old('death_cause', $animal->death_cause) }}">
                                    @error('death_cause')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Source -->
                                <div class="mb-3">
                                    <label for="source" class="form-label">
                                        Source <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('source') is-invalid @enderror" 
                                            id="source" 
                                            name="source" 
                                            required>
                                        <option value="">Select Source</option>
                                        <option value="born" {{ old('source', $animal->source) == 'born' ? 'selected' : '' }}>Born on Farm</option>
                                        <option value="purchased" {{ old('source', $animal->source) == 'purchased' ? 'selected' : '' }}>Purchased</option>
                                    </select>
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date Added -->
                                <div class="mb-3">
                                    <label for="date_added" class="form-label">
                                        Date Added to Herd <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('date_added') is-invalid @enderror" 
                                           id="date_added" 
                                           name="date_added" 
                                           value="{{ old('date_added', $animal->date_added->format('Y-m-d')) }}" 
                                           required>
                                    @error('date_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Parents Information -->
                                <h6 class="text-success mb-3 border-bottom pb-2 mt-4">
                                    <i class="fas fa-people-roof me-2"></i>Parent Information
                                </h6>

                                <!-- Dam (Mother) -->
                                <div class="mb-3">
                                    <label for="dam_id" class="form-label">Dam (Mother)</label>
                                    <select class="form-select @error('dam_id') is-invalid @enderror" 
                                            id="dam_id" 
                                            name="dam_id">
                                        <option value="">Select Dam (optional)</option>
                                        @foreach($animals->where('sex', 'Female') as $dam)
                                            <option value="{{ $dam->id }}" 
                                                    {{ old('dam_id', $animal->dam_id) == $dam->id ? 'selected' : '' }}>
                                                {{ $dam->animal_id }} - {{ $dam->name ?? 'Unnamed' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dam_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sire (Father) -->
                                <div class="mb-3">
                                    <label for="sire_id" class="form-label">Sire (Father)</label>
                                    <select class="form-select @error('sire_id') is-invalid @enderror" 
                                            id="sire_id" 
                                            name="sire_id">
                                        <option value="">Select Sire (optional)</option>
                                        @foreach($animals->where('sex', 'Male') as $sire)
                                            <option value="{{ $sire->id }}" 
                                                    {{ old('sire_id', $animal->sire_id) == $sire->id ? 'selected' : '' }}>
                                                {{ $sire->animal_id }} - {{ $sire->name ?? 'Unnamed' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sire_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Active Status -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', $animal->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active in Herd
                                        </label>
                                        <div class="form-text">
                                            Uncheck if animal is not currently part of the active herd (sold, dead, etc.)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('animals.show', $animal) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-2"></i>Update Animal
                                        </button>
                                        <a href="{{ route('animals.index') }}" class="btn btn-outline-primary ms-2">
                                            <i class="fas fa-list me-2"></i>Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
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
        // Function to toggle status-specific fields
        window.toggleStatusFields = function() {
            const status = document.getElementById('status').value;
            
            // Hide all fields first
            document.getElementById('soldFields').style.display = 'none';
            document.getElementById('deadFields').style.display = 'none';
            
            // Show relevant fields based on status
            if (status === 'sold') {
                document.getElementById('soldFields').style.display = 'block';
                // Make date_sold required when status is sold
                document.getElementById('date_sold').setAttribute('required', 'required');
            } else {
                document.getElementById('date_sold').removeAttribute('required');
            }
            
            if (status === 'dead') {
                document.getElementById('deadFields').style.display = 'block';
                // Make date_died and death_cause required when status is dead
                document.getElementById('date_died').setAttribute('required', 'required');
                document.getElementById('death_cause').setAttribute('required', 'required');
            } else {
                document.getElementById('date_died').removeAttribute('required');
                document.getElementById('death_cause').removeAttribute('required');
            }
        }
        
        // Initialize on page load
        toggleStatusFields();
        
        // Preview photo before upload
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Show preview (you could enhance this with a preview modal)
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Create or update preview image
                        let preview = document.getElementById('photoPreview');
                        if (!preview) {
                            preview = document.createElement('img');
                            preview.id = 'photoPreview';
                            preview.className = 'img-thumbnail mt-2';
                            preview.style.maxWidth = '200px';
                            preview.style.maxHeight = '200px';
                            photoInput.parentNode.appendChild(preview);
                        }
                        preview.src = e.target.result;
                        preview.alt = 'New photo preview';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Form validation before submit
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;
            
            if (status === 'sold') {
                const dateSold = document.getElementById('date_sold').value;
                if (!dateSold) {
                    e.preventDefault();
                    alert('Please enter the date sold for sold animals.');
                    document.getElementById('date_sold').focus();
                    return false;
                }
            }
            
            if (status === 'dead') {
                const dateDied = document.getElementById('date_died').value;
                const deathCause = document.getElementById('death_cause').value;
                
                if (!dateDied || !deathCause.trim()) {
                    e.preventDefault();
                    alert('Please enter both date of death and cause for dead animals.');
                    if (!dateDied) document.getElementById('date_died').focus();
                    else document.getElementById('death_cause').focus();
                    return false;
                }
            }
            
            return true;
        });
    });
</script>
@endpush

@push('styles')
<style>
    .form-check-input:checked {
        background-color: var(--farm-green);
        border-color: var(--farm-green);
    }
    
    .form-check-input:focus {
        border-color: var(--farm-green-light);
        box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
    }
    
    .status-fields {
        border-left: 3px solid var(--farm-green);
        padding-left: 15px;
        margin-left: 5px;
        background-color: rgba(46, 125, 50, 0.05);
        border-radius: 0 5px 5px 0;
    }
    
    label.required::after {
        content: " *";
        color: #dc3545;
    }
    
    /* Make sure file input doesn't overflow */
    input[type="file"] {
        overflow: hidden;
    }
    
    /* Improve select appearance */
    .form-select:focus {
        border-color: var(--farm-green);
        box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
    }
</style>
@endpush