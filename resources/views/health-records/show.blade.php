@extends('layouts.app')

@section('title', 'Health Record Details - Dairy Farm Management')
@section('page-title', 'Health Record Details')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('health-records.index') }}">Health Records</a>
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
                        <i class="fas fa-file-medical me-2 text-danger"></i>
                        Health Record Details
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('health-records.edit', $healthRecord) }}" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('health-records.destroy', $healthRecord) }}" 
                              method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this health record?')"
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
                                    <th width="40%">Date:</th>
                                    <td>{{ $healthRecord->date->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Animal:</th>
                                    <td>
                                        <a href="{{ route('animals.show', $healthRecord->animal_id) }}">
                                            <strong>{{ $healthRecord->animal->animal_id }}</strong>
                                            @if($healthRecord->animal->name)
                                                - {{ $healthRecord->animal->name }}
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Breed:</th>
                                    <td>{{ $healthRecord->animal->breed }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $healthRecord->animal->status == 'lactating' ? 'success' : 'secondary' }}">
                                            {{ $healthRecord->animal->status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Outcome:</th>
                                    <td>
                                        @if($healthRecord->outcome === 'Recovered')
                                            <span class="badge bg-success">Recovered</span>
                                        @elseif($healthRecord->outcome === 'Under Treatment')
                                            <span class="badge bg-warning">Under Treatment</span>
                                        @elseif($healthRecord->outcome === 'Not Responding')
                                            <span class="badge bg-danger">Not Responding</span>
                                        @elseif($healthRecord->outcome === 'Died')
                                            <span class="badge bg-dark">Died</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Veterinarian:</th>
                                    <td>{{ $healthRecord->veterinarian ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $healthRecord->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $healthRecord->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Diagnosis & Symptoms</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-danger">{{ $healthRecord->diagnosis }}</h6>
                                    @if($healthRecord->clinical_signs)
                                        <p class="mb-0"><strong>Clinical Signs:</strong> {{ $healthRecord->clinical_signs }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Treatment Information</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $healthRecord->treatment }}</p>
                                    
                                    @if($healthRecord->drug_name || $healthRecord->dosage || $healthRecord->route || $healthRecord->duration)
                                        <div class="row">
                                            @if($healthRecord->drug_name)
                                                <div class="col-md-3">
                                                    <strong>Drug:</strong><br>
                                                    {{ $healthRecord->drug_name }}
                                                </div>
                                            @endif
                                            @if($healthRecord->dosage)
                                                <div class="col-md-3">
                                                    <strong>Dosage:</strong><br>
                                                    {{ $healthRecord->dosage }}
                                                </div>
                                            @endif
                                            @if($healthRecord->route)
                                                <div class="col-md-3">
                                                    <strong>Route:</strong><br>
                                                    {{ $healthRecord->route }}
                                                </div>
                                            @endif
                                            @if($healthRecord->duration)
                                                <div class="col-md-3">
                                                    <strong>Duration:</strong><br>
                                                    {{ $healthRecord->duration }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($healthRecord->milk_withdrawal_days || $healthRecord->meat_withdrawal_days || $healthRecord->notes)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($healthRecord->milk_withdrawal_days)
                                            <div class="col-md-6 mb-2">
                                                <span class="badge bg-warning">Milk Withdrawal: {{ $healthRecord->milk_withdrawal_days }} days</span>
                                            </div>
                                        @endif
                                        @if($healthRecord->meat_withdrawal_days)
                                            <div class="col-md-6 mb-2">
                                                <span class="badge bg-danger">Meat Withdrawal: {{ $healthRecord->meat_withdrawal_days }} days</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($healthRecord->notes)
                                        <div class="mt-3">
                                            <strong>Notes:</strong>
                                            <p class="mb-0">{{ $healthRecord->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
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
                        @if($healthRecord->animal->photo)
                            <img src="{{ Storage::url($healthRecord->animal->photo) }}" 
                                 alt="Animal Photo" 
                                 class="img-thumbnail mb-2"
                                 style="max-width: 150px;">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2"
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-cow fa-3x text-muted"></i>
                            </div>
                        @endif
                        <h5>{{ $healthRecord->animal->name ?? 'Unnamed' }}</h5>
                        <p class="text-muted mb-0">{{ $healthRecord->animal->animal_id }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Breed:</span>
                            <span>{{ $healthRecord->animal->breed }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Age:</span>
                            <span>{{ \Carbon\Carbon::parse($healthRecord->animal->date_of_birth)->age }} years</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-{{ $healthRecord->animal->status == 'lactating' ? 'success' : 'secondary' }}">
                                {{ $healthRecord->animal->status }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Health Status:</span>
                            <span class="badge bg-{{ $healthRecord->outcome == 'Recovered' ? 'success' : ($healthRecord->outcome == 'Under Treatment' ? 'warning' : 'danger') }}">
                                {{ $healthRecord->outcome }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('animals.show', $healthRecord->animal_id) }}" 
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
                        <a href="{{ route('health-records.create', ['animal_id' => $healthRecord->animal_id]) }}" 
                           class="btn btn-danger">
                            <i class="fas fa-plus-circle me-2"></i>New Record for Same Animal
                        </a>
                        <a href="{{ route('health-records.create') }}" class="btn btn-primary">
                            <i class="fas fa-heartbeat me-2"></i>New Health Record
                        </a>
                        <a href="{{ route('health-records.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>All Records
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection