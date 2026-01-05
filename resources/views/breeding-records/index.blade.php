@extends('layouts.app')

@section('title', 'Breeding Records - Dairy Farm Management')
@section('page-title', 'Breeding Records')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Breeding Records</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pregnant Cows</h6>
                            <h3 class="mb-0">{{ $pregnantCows }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-baby fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Diagnosis</h6>
                            <h3 class="mb-0">{{ $pendingDiagnosis }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Records</h6>
                            <h3 class="mb-0">{{ $breedingRecords->total() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-dna fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Breeding Management</h5>
                        <div>
                            <a href="{{ route('breeding-records.create') }}" class="btn btn-warning">
                                <i class="fas fa-plus me-2"></i>New Breeding Record
                            </a>
                            <a href="{{ route('breeding-records.pregnant') }}" class="btn btn-success ms-2">
                                <i class="fas fa-baby me-2"></i>Pregnant Animals
                            </a>
                            <a href="{{ route('breeding-records.due-for-calving') }}" class="btn btn-danger ms-2">
                                <i class="fas fa-clock me-2"></i>Due for Calving
                            </a>
                            <a href="{{ route('breeding-records.calendar') }}" class="btn btn-info ms-2">
                                <i class="fas fa-calendar me-2"></i>Breeding Calendar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breeding Records Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-dna me-2"></i>
                Recent Breeding Records
            </h5>
        </div>
        <div class="card-body">
            @if($breedingRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date of Service</th>
                                <th>Animal</th>
                                <th>Method</th>
                                <th>Bull/Semen ID</th>
                                <th>Pregnancy Result</th>
                                <th>Expected Calving</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($breedingRecords as $record)
                            <tr>
                                <td>{{ $record->date_of_service->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('animals.show', $record->animal_id) }}">
                                        <strong>{{ $record->animal->animal_id }}</strong>
                                        @if($record->animal->name)
                                            <br><small>{{ $record->animal->name }}</small>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $record->breeding_method == 'AI' ? 'info' : ($record->breeding_method == 'Natural' ? 'success' : 'warning') }}">
                                        {{ $record->breeding_method }}
                                    </span>
                                </td>
                                <td>{{ $record->bull_semen_id ?? 'N/A' }}</td>
                                <td>
                                    @if($record->pregnancy_result === true)
                                        <span class="badge bg-success">Pregnant</span>
                                    @elseif($record->pregnancy_result === false)
                                        <span class="badge bg-danger">Not Pregnant</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->expected_calving_date)
                                        @if($record->expected_calving_date->isPast() && !$record->actual_calving_date)
                                            <span class="badge bg-danger">
                                                {{ $record->expected_calving_date->format('M d') }}
                                            </span>
                                        @elseif($record->expected_calving_date->diffInDays(now()) <= 30)
                                            <span class="badge bg-warning">
                                                {{ $record->expected_calving_date->format('M d') }}
                                            </span>
                                        @else
                                            {{ $record->expected_calving_date->format('M d, Y') }}
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
                                        <form action="{{ route('breeding-records.destroy', $record) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this breeding record?')"
                                                    class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                    <i class="fas fa-dna fa-4x text-muted mb-3"></i>
                    <h4>No Breeding Records</h4>
                    <p class="text-muted">Start recording breeding activities to see data here.</p>
                    <a href="{{ route('breeding-records.create') }}" class="btn btn-warning">
                        <i class="fas fa-plus me-2"></i>Add First Record
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection