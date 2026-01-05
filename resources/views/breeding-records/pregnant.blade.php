@extends('layouts.app')

@section('title', 'Pregnant Animals - Dairy Farm Management')
@section('page-title', 'Pregnant Animals')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('breeding-records.index') }}">Breeding Records</a>
    </li>
    <li class="breadcrumb-item active">Pregnant Animals</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-baby me-2 text-success"></i>
                    Confirmed Pregnant Animals
                </h5>
                <div>
                    <span class="badge bg-success">{{ $breedingRecords->total() }} pregnant</span>
                    <a href="{{ route('breeding-records.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i>All Records
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pregnant Animals List -->
    <div class="card">
        <div class="card-body">
            @if($breedingRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Animal</th>
                                <th>Date of Service</th>
                                <th>Breeding Method</th>
                                <th>Pregnancy Check Date</th>
                                <th>Expected Calving</th>
                                <th>Days to Calving</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($breedingRecords as $record)
                            @php
                                $daysToCalving = $record->expected_calving_date ? now()->diffInDays($record->expected_calving_date, false) : null;
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('animals.show', $record->animal_id) }}">
                                        <strong>{{ $record->animal->animal_id }}</strong>
                                        @if($record->animal->name)
                                            <br><small>{{ $record->animal->name }}</small>
                                        @endif
                                        <br><small class="text-muted">{{ $record->animal->breed }}</small>
                                    </a>
                                </td>
                                <td>{{ $record->date_of_service->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $record->breeding_method == 'AI' ? 'info' : ($record->breeding_method == 'Natural' ? 'success' : 'warning') }}">
                                        {{ $record->breeding_method }}
                                    </span>
                                </td>
                                <td>
                                    @if($record->pregnancy_diagnosis_date)
                                        {{ $record->pregnancy_diagnosis_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not recorded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->expected_calving_date)
                                        <strong>{{ $record->expected_calving_date->format('M d, Y') }}</strong>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($daysToCalving !== null)
                                        @if($daysToCalving <= 0)
                                            <span class="badge bg-danger">
                                                {{ abs($daysToCalving) }} days overdue
                                            </span>
                                        @elseif($daysToCalving <= 30)
                                            <span class="badge bg-warning">
                                                {{ $daysToCalving }} days
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                {{ $daysToCalving }} days
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('breeding-records.show', $record) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('breeding-records.edit', $record) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('animals.show', $record->animal_id) }}" 
                                           class="btn btn-outline-info">
                                            <i class="fas fa-cow"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $breedingRecords->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>No Pregnant Animals</h4>
                    <p class="text-muted">There are currently no confirmed pregnant animals.</p>
                    <a href="{{ route('breeding-records.create') }}" class="btn btn-warning">
                        <i class="fas fa-plus me-2"></i>Add Breeding Record
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Pregnancy Statistics -->
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-calendar-alt fa-3x mb-3 opacity-50"></i>
                        <h3>
                            @php
                                $dueNextMonth = collect($breedingRecords->items())->filter(function($record) {
                                    return $record->expected_calving_date && 
                                           $record->expected_calving_date->between(now(), now()->addDays(30));
                                })->count();
                            @endphp
                            {{ $dueNextMonth }}
                        </h3>
                        <h6>Due in Next 30 Days</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-50"></i>
                        <h3>
                            @php
                                $overdue = collect($breedingRecords->items())->filter(function($record) {
                                    return $record->expected_calving_date && 
                                           $record->expected_calving_date->isPast();
                                })->count();
                            @endphp
                            {{ $overdue }}
                        </h3>
                        <h6>Overdue for Calving</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-clock fa-3x mb-3 opacity-50"></i>
                        <h3>
                            @php
                                $noExpectedDate = collect($breedingRecords->items())->filter(function($record) {
                                    return !$record->expected_calving_date;
                                })->count();
                            @endphp
                            {{ $noExpectedDate }}
                        </h3>
                        <h6>Missing Expected Date</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection