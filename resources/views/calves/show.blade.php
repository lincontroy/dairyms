@extends('layouts.app')

@section('title', $calf->calf_id . ' - Calf Details - Dairy Farm')
@section('page-title', 'Calf Details: ' . $calf->calf_id)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('calves.index') }}">Calves</a></li>
<li class="breadcrumb-item active">{{ $calf->calf_id }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Column: Calf Information -->
        <div class="col-lg-8">
            <!-- Calf Overview Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-baby text-primary me-2"></i>
                        Calf Overview
                        <div class="float-end">
                            <a href="{{ route('calves.edit', $calf) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        </div>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="calf-avatar mb-3">
                                <i class="fas fa-baby fa-5x {{ $calf->sex == 'male' ? 'text-info' : 'text-pink' }}"></i>
                            </div>
                            <h4 class="mb-1">{{ $calf->name ?? 'Unnamed' }}</h4>
                            <h6 class="text-muted">{{ $calf->calf_id }}</h6>
                            <div class="mt-2">
                                <span class="badge {{ $calf->sex == 'male' ? 'bg-info' : 'bg-pink' }}">
                                    {{ ucfirst($calf->sex) }}
                                </span>
                                <span class="badge bg-success ms-1">{{ ucfirst($calf->status) }}</span>
                                @if($calf->is_weaned)
                                <span class="badge bg-primary ms-1">Weaned</span>
                                @endif
                                @if($calf->requires_special_care)
                                <span class="badge bg-danger ms-1">Special Care</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Ear Tag:</strong><br>
                                    <span class="badge bg-secondary fs-6">{{ $calf->ear_tag }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Date of Birth:</strong><br>
                                    {{ $calf->date_of_birth->format('F d, Y') }}
                                    <small class="text-muted d-block">
                                        {{ $calf->age_in_days }} days old ({{ $calf->age_in_months }} months)
                                    </small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Birth Weight:</strong><br>
                                    @if($calf->birth_weight)
                                    {{ $calf->birth_weight }} kg
                                    @else
                                    <span class="text-muted">Not recorded</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Birth Type:</strong><br>
                                    <span class="text-capitalize">{{ $calf->birth_type }}</span> birth
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Delivery Type:</strong><br>
                                    <span class="text-capitalize">{{ $calf->delivery_type }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Health Status:</strong><br>
                                    @switch($calf->health_status)
                                        @case('excellent')
                                            <span class="badge bg-success">Excellent</span>
                                            @break
                                        @case('good')
                                            <span class="badge bg-info">Good</span>
                                            @break
                                        @case('fair')
                                            <span class="badge bg-warning">Fair</span>
                                            @break
                                        @case('poor')
                                            <span class="badge bg-danger">Poor</span>
                                            @break
                                    @endswitch
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Vaccination Status:</strong><br>
                                    @switch($calf->vaccination_status)
                                        @case('complete')
                                            <span class="badge bg-success">Complete</span>
                                            @break
                                        @case('partial')
                                            <span class="badge bg-warning">Partial</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-danger">Pending</span>
                                            @break
                                    @endswitch
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Color & Markings:</strong><br>
                                    {{ $calf->color_markings ?? 'Not specified' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        Parent Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Dam Information -->
                        <div class="col-md-6">
                            <div class="card border-primary mb-3 h-100">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-cow me-2"></i> Dam (Mother)
                                </div>
                                <div class="card-body">
                                    @if($calf->dam)
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-female fa-3x text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">{{ $calf->dam->name }}</h5>
                                            <p class="mb-1">
                                                <strong>Ear Tag:</strong> {{ $calf->dam->ear_tag }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Breed:</strong> {{ $calf->dam->breed }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>Status:</strong> {{ ucfirst($calf->dam->status) }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('animals.show', $calf->dam) }}" 
                                       class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-external-link-alt me-1"></i> View Dam Details
                                    </a>
                                    @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-question-circle fa-3x mb-3"></i>
                                        <p>Dam information not available</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sire Information -->
                        <div class="col-md-6">
                            <div class="card border-info mb-3 h-100">
                                <div class="card-header bg-info text-white">
                                    <i class="fas fa-bull me-2"></i> Sire (Father)
                                </div>
                                <div class="card-body">
                                    @if($calf->sire)
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-male fa-3x text-info"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">{{ $calf->sire->name }}</h5>
                                            <p class="mb-1">
                                                <strong>Ear Tag:</strong> {{ $calf->sire->ear_tag }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Breed:</strong> {{ $calf->sire->breed }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>Status:</strong> {{ ucfirst($calf->sire->status) }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('animals.show', $calf->sire) }}" 
                                       class="btn btn-outline-info btn-sm w-100">
                                        <i class="fas fa-external-link-alt me-1"></i> View Sire Details
                                    </a>
                                    @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-question-circle fa-3x mb-3"></i>
                                        <p>Sire information not available</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                  <!-- Breeding Record -->
@if($calf->breedingRecord)
<div class="row mt-3">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <i class="fas fa-dna me-2"></i> Related Breeding Record
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Breeding Date:</strong><br>
                        {{ $calf->breedingRecord->date_of_service->format('F d, Y') ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Expected Calving:</strong><br>
                        {{ $calf->breedingRecord->expected_calving_date?->format('F d, Y') ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Actual Calving:</strong><br>
                        {{ $calf->breedingRecord->actual_calving_date?->format('F d, Y') ?? 'N/A' }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <strong>Breeding Method:</strong><br>
                        {{ $calf->breedingRecord->breeding_method ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Calves Born:</strong><br>
                        {{ $calf->breedingRecord->calves_born ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Calves Alive:</strong><br>
                        {{ $calf->breedingRecord->calves_alive ?? 'N/A' }}
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('breeding-records.show', $calf->breedingRecord) }}" 
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i> View Breeding Record Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
                </div>
            </div>

            <!-- Notes Card -->
            @if($calf->notes || $calf->special_care_notes)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note text-primary me-2"></i>
                        Notes
                    </h5>
                </div>
                <div class="card-body">
                    @if($calf->special_care_notes)
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-medkit me-2"></i> Special Care Required</h6>
                        <p class="mb-0">{{ $calf->special_care_notes }}</p>
                    </div>
                    @endif

                    @if($calf->notes)
                    <div class="mb-0">
                        <h6>General Notes:</h6>
                        <div class="notes-content">
                            {!! nl2br(e($calf->notes)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Actions & Siblings -->
        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($calf->status == 'alive')
                    <div class="d-grid gap-2">
                        <!-- Mark as Weaned -->
                        @if(!$calf->is_weaned && $stats['is_weaning_due'])
                        <button type="button" 
                                class="btn btn-success mb-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#weanModal">
                            <i class="fas fa-check-circle me-1"></i> Mark as Weaned
                        </button>
                        @endif

                        <!-- Update Health Status -->
                        <button type="button" 
                                class="btn btn-info mb-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#healthModal">
                            <i class="fas fa-heartbeat me-1"></i> Update Health Status
                        </button>

                        <!-- Record Death -->
                        @if($calf->status == 'alive')
                        <button type="button" 
                                class="btn btn-danger mb-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deathModal">
                            <i class="fas fa-skull-crossbones me-1"></i> Record Death
                        </button>
                        @endif
                    </div>
                    @endif

                    <!-- General Actions -->
                    <div class="list-group mt-3">
                        <a href="{{ route('calves.edit', $calf) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-edit text-warning me-2"></i> Edit Calf Details
                        </a>
                        <a href="{{ route('calves.by-dam', $calf->dam) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cow text-primary me-2"></i> View All Calves from this Dam
                        </a>
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('calves.destroy', $calf) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this calf? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="list-group-item list-group-item-action text-danger">
                                <i class="fas fa-trash me-2"></i> Delete Calf Record
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Siblings Card -->
            @if($siblings->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-friends text-primary me-2"></i>
                        Siblings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($siblings as $sibling)
                        <a href="{{ route('calves.show', $sibling) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $sibling->name ?? 'Unnamed' }}</strong><br>
                                <small class="text-muted">
                                    {{ $sibling->calf_id }} • 
                                    {{ $sibling->age_in_days }} days old
                                </small>
                            </div>
                            <span class="badge {{ $sibling->sex == 'male' ? 'bg-info' : 'bg-pink' }}">
                                {{ ucfirst($sibling->sex) }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Record Information Card -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Record Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Recorded By:</strong><br>
                        {{ $calf->recordedBy->name }}<br>
                        <small class="text-muted">{{ $calf->recordedBy->role }}</small>
                    </div>
                    <div class="mb-3">
                        <strong>Date Recorded:</strong><br>
                        {{ $calf->created_at->format('F d, Y \a\t H:i') }}
                    </div>
                    <div>
                        <strong>Last Updated:</strong><br>
                        {{ $calf->updated_at->format('F d, Y \a\t H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Actions -->
@if($calf->status == 'alive')
<!-- Wean Modal -->
<div class="modal fade" id="weanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('calves.mark-weaned', $calf) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Mark Calf as Weaned
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are marking <strong>{{ $calf->name ?? $calf->calf_id }}</strong> as weaned.</p>
                    
                    <div class="mb-3">
                        <label for="weaning_weight" class="form-label">Weaning Weight (kg) *</label>
                        <div class="input-group">
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="500"
                                   class="form-control" 
                                   id="weaning_weight" 
                                   name="weaning_weight"
                                   required>
                            <span class="input-group-text">kg</span>
                        </div>
                        <small class="form-text text-muted">
                            Enter the weight at weaning time
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Weaned</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Health Status Modal -->
<div class="modal fade" id="healthModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('calves.update-health-status', $calf) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-heartbeat text-info me-2"></i>
                        Update Health Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Update health status for <strong>{{ $calf->name ?? $calf->calf_id }}</strong>.</p>
                    
                    <div class="mb-3">
                        <label for="health_status" class="form-label">Health Status *</label>
                        <select class="form-select" id="health_status" name="health_status" required>
                            <option value="excellent" {{ $calf->health_status == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ $calf->health_status == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ $calf->health_status == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ $calf->health_status == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Add any notes about health status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Death Record Modal -->
<div class="modal fade" id="deathModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('calves.record-death', $calf) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-skull-crossbones text-danger me-2"></i>
                        Record Calf Death
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. The calf will be marked as deceased.
                    </div>
                    
                    <p>Recording death for <strong>{{ $calf->name ?? $calf->calf_id }}</strong>.</p>
                    
                    <div class="mb-3">
                        <label for="death_date" class="form-label">Date of Death *</label>
                        <input type="date" 
                               class="form-control" 
                               id="death_date" 
                               name="death_date"
                               value="{{ date('Y-m-d') }}"
                               max="{{ date('Y-m-d') }}"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="cause_of_death" class="form-label">Cause of Death *</label>
                        <input type="text" 
                               class="form-control" 
                               id="cause_of_death" 
                               name="cause_of_death"
                               placeholder="e.g., Illness, Accident, Unknown..."
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Any additional details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Record Death</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .badge.bg-pink {
        background-color: #e83e8c;
        color: white;
    }
    
    .calf-avatar {
        padding: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: inline-block;
        border: 3px solid #dee2e6;
    }
    
    .notes-content {
        background-color: #f8f9fa;
        border-left: 4px solid #2E7D32;
        padding: 15px;
        border-radius: 4px;
        white-space: pre-line;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set max date for death date
    const deathDate = document.getElementById('death_date');
    if (deathDate) {
        deathDate.max = new Date().toISOString().split('T')[0];
    }
});
</script>
@endpush