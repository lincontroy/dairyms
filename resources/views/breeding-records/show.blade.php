@extends('layouts.app')

@section('title', 'Breeding Record Details - Dairy Farm Management')
@section('page-title', 'Breeding Record Details')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('breeding-records.index') }}">Breeding Records</a>
    </li>
    <li class="breadcrumb-item active">Record Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-dna me-2 text-warning"></i>
                        Breeding Record Details
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('breeding-records.edit', $breedingRecord) }}" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('breeding-records.destroy', $breedingRecord) }}" 
                              method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this breeding record?')"
                                    class="btn btn-danger btn-sm ms-1">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Date of Service:</th>
                                    <td>{{ $breedingRecord->date_of_service->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Animal:</th>
                                    <td>
                                        <a href="{{ route('animals.show', $breedingRecord->animal_id) }}">
                                            <strong>{{ $breedingRecord->animal->animal_id }}</strong>
                                            @if($breedingRecord->animal->name)
                                                - {{ $breedingRecord->animal->name }}
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Breed:</th>
                                    <td>{{ $breedingRecord->animal->breed }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $breedingRecord->animal->status == 'pregnant' ? 'warning' : 'secondary' }}">
                                            {{ $breedingRecord->animal->status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Breeding Method:</th>
                                    <td>
                                        <span class="badge bg-{{ $breedingRecord->breeding_method == 'AI' ? 'info' : ($breedingRecord->breeding_method == 'Natural' ? 'success' : 'warning') }}">
                                            {{ $breedingRecord->breeding_method }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bull/Semen ID:</th>
                                    <td>{{ $breedingRecord->bull_semen_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Technician:</th>
                                    <td>{{ $breedingRecord->technician ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $breedingRecord->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <!-- Pregnancy Status -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Pregnancy Status</h6>
                                </div>
                                <div class="card-body">
                                    @if($breedingRecord->pregnancy_result === true)
                                        <div class="text-center">
                                            <i class="fas fa-baby fa-3x text-success mb-2"></i>
                                            <h5 class="text-success mb-1">Confirmed Pregnant</h5>
                                            @if($breedingRecord->pregnancy_diagnosis_date)
                                                <p class="mb-1">Diagnosed: {{ $breedingRecord->pregnancy_diagnosis_date->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                    @elseif($breedingRecord->pregnancy_result === false)
                                        <div class="text-center">
                                            <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                                            <h5 class="text-danger mb-1">Not Pregnant</h5>
                                            @if($breedingRecord->pregnancy_diagnosis_date)
                                                <p class="mb-1">Checked: {{ $breedingRecord->pregnancy_diagnosis_date->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                                            <h5 class="text-warning mb-1">Pending Diagnosis</h5>
                                            <p class="mb-0">Awaiting pregnancy check</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calving Information -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Calving Information</h6>
                                </div>
                                <div class="card-body">
                                    @if($breedingRecord->expected_calving_date)
                                        <div class="text-center">
                                            @php
                                                $daysToCalving = now()->diffInDays($breedingRecord->expected_calving_date, false);
                                            @endphp
                                            
                                            @if($breedingRecord->actual_calving_date)
                                                <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                                                <h5 class="text-success mb-1">Calved</h5>
                                                <p class="mb-1">
                                                    {{ $breedingRecord->actual_calving_date->format('M d, Y') }}
                                                </p>
                                                @if($breedingRecord->calving_outcome)
                                                    <span class="badge bg-info">{{ $breedingRecord->calving_outcome }}</span>
                                                @endif
                                            @elseif($daysToCalving <= 0)
                                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-2"></i>
                                                <h5 class="text-danger mb-1">Past Due</h5>
                                                <p class="mb-1">
                                                    {{ abs($daysToCalving) }} days overdue
                                                </p>
                                            @elseif($daysToCalving <= 30)
                                                <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                                                <h5 class="text-warning mb-1">Due Soon</h5>
                                                <p class="mb-1">
                                                    {{ $daysToCalving }} days to go
                                                </p>
                                                <p class="mb-0">
                                                    Expected: {{ $breedingRecord->expected_calving_date->format('M d, Y') }}
                                                </p>
                                            @else
                                                <i class="fas fa-calendar-alt fa-3x text-info mb-2"></i>
                                                <h5 class="text-info mb-1">Expected Calving</h5>
                                                <p class="mb-0">
                                                    {{ $breedingRecord->expected_calving_date->format('M d, Y') }}
                                                </p>
                                                <small class="text-muted">
                                                    ({{ $daysToCalving }} days from now)
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-calendar fa-3x text-muted mb-2"></i>
                                            <h5 class="text-muted mb-1">No Calving Date Set</h5>
                                            <p class="mb-0">Awaiting pregnancy confirmation</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Breeding Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-step">
                                    <div class="timeline-icon {{ $breedingRecord->date_of_service ? 'bg-success' : 'bg-light' }}">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Date of Service</h6>
                                        <p>{{ $breedingRecord->date_of_service->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="timeline-step">
                                    <div class="timeline-icon {{ $breedingRecord->pregnancy_diagnosis_date ? 'bg-info' : 'bg-light' }}">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Pregnancy Diagnosis</h6>
                                        <p>
                                            @if($breedingRecord->pregnancy_diagnosis_date)
                                                {{ $breedingRecord->pregnancy_diagnosis_date->format('M d, Y') }}
                                                <br>
                                                <span class="badge bg-{{ $breedingRecord->pregnancy_result ? 'success' : 'danger' }}">
                                                    {{ $breedingRecord->pregnancy_result ? 'Pregnant' : 'Not Pregnant' }}
                                                </span>
                                            @else
                                                <span class="text-muted">Pending</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="timeline-step">
                                    <div class="timeline-icon {{ $breedingRecord->expected_calving_date ? 'bg-warning' : 'bg-light' }}">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Expected Calving</h6>
                                        <p>
                                            @if($breedingRecord->expected_calving_date)
                                                {{ $breedingRecord->expected_calving_date->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="timeline-step">
                                    <div class="timeline-icon {{ $breedingRecord->actual_calving_date ? 'bg-success' : 'bg-light' }}">
                                        <i class="fas fa-baby"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Actual Calving</h6>
                                        <p>
                                            @if($breedingRecord->actual_calving_date)
                                                {{ $breedingRecord->actual_calving_date->format('M d, Y') }}
                                                @if($breedingRecord->calving_outcome)
                                                    <br>
                                                    <span class="badge bg-info">{{ $breedingRecord->calving_outcome }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Pending</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    @if($breedingRecord->notes)
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Notes</h6>
                        </div>
                        <div class="card-body">
                            <p>{{ $breedingRecord->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Related Animal Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-cow me-2"></i>
                        Animal Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($breedingRecord->animal->photo)
                            <img src="{{ Storage::url($breedingRecord->animal->photo) }}" 
                                 alt="Animal Photo" 
                                 class="img-thumbnail mb-2"
                                 style="max-width: 150px;">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2"
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-cow fa-3x text-muted"></i>
                            </div>
                        @endif
                        <h5>{{ $breedingRecord->animal->name ?? 'Unnamed' }}</h5>
                        <p class="text-muted mb-0">{{ $breedingRecord->animal->animal_id }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Breed:</span>
                            <span>{{ $breedingRecord->animal->breed }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Age:</span>
                            <span>{{ \Carbon\Carbon::parse($breedingRecord->animal->date_of_birth)->age }} years</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-{{ $breedingRecord->animal->status == 'pregnant' ? 'warning' : 'secondary' }}">
                                {{ $breedingRecord->animal->status }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Date Added:</span>
                            <span>{{ $breedingRecord->animal->date_added->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('animals.show', $breedingRecord->animal_id) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-external-link-alt me-1"></i>View Animal Details
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($breedingRecord->pregnancy_result === true && !$breedingRecord->actual_calving_date)
                            <a href="{{ route('breeding-records.edit', $breedingRecord) }}" 
                               class="btn btn-success">
                                <i class="fas fa-baby me-2"></i>Record Calving
                            </a>
                        @endif
                        
                        @if(!$breedingRecord->pregnancy_diagnosis_date && $breedingRecord->date_of_service->diffInDays(now()) >= 28)
                            <a href="{{ route('breeding-records.edit', $breedingRecord) }}" 
                               class="btn btn-info">
                                <i class="fas fa-stethoscope me-2"></i>Record Pregnancy Check
                            </a>
                        @endif
                        
                        <a href="{{ route('breeding-records.create', ['animal_id' => $breedingRecord->animal_id]) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-plus-circle me-2"></i>New Breeding for Same Animal
                        </a>
                        <a href="{{ route('breeding-records.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>All Breeding Records
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-step {
        position: relative;
        padding-bottom: 20px;
        display: flex;
    }
    
    .timeline-step:last-child {
        padding-bottom: 0;
    }
    
    .timeline-step:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-step:last-child:before {
        display: none;
    }
    
    .timeline-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        z-index: 1;
    }
    
    .timeline-content {
        flex-grow: 1;
    }
    
    .timeline-content h6 {
        margin-bottom: 5px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .timeline-content p {
        margin-bottom: 0;
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>
@endpush
@endsection