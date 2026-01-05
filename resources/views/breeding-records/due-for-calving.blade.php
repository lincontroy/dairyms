@extends('layouts.app')

@section('title', 'Animals Due for Calving - Dairy Farm Management')
@section('page-title', 'Animals Due for Calving')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('breeding-records.index') }}">Breeding Records</a>
    </li>
    <li class="breadcrumb-item active">Due for Calving</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2 text-danger"></i>
                    Animals Due for Calving (Next 30 Days)
                </h5>
                <div>
                    <span class="badge bg-danger">{{ $breedingRecords->total() }} due soon</span>
                    <a href="{{ route('breeding-records.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i>All Records
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Due for Calving List -->
    <div class="card">
        <div class="card-body">
            @if($breedingRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Due Date</th>
                                <th>Animal</th>
                                <th>Days to Go</th>
                                <th>Date of Service</th>
                                <th>Breeding Method</th>
                                <th>Pregnancy Check</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($breedingRecords as $record)
                            @php
                                $daysToCalving = now()->diffInDays($record->expected_calving_date, false);
                                $progress = max(0, min(100, (283 - $daysToCalving) / 283 * 100));
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $record->expected_calving_date->format('M d, Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $record->expected_calving_date->format('l') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('animals.show', $record->animal_id) }}">
                                        <strong>{{ $record->animal->animal_id }}</strong>
                                        @if($record->animal->name)
                                            <br><small>{{ $record->animal->name }}</small>
                                        @endif
                                        <br><small class="text-muted">{{ $record->animal->breed }}</small>
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                            <div class="progress-bar 
                                                @if($daysToCalving <= 7) bg-danger
                                                @elseif($daysToCalving <= 14) bg-warning
                                                @else bg-success @endif" 
                                                role="progressbar" 
                                                style="width: {{ $progress }}%"
                                                aria-valuenow="{{ $progress }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="badge bg-{{ $daysToCalving <= 7 ? 'danger' : ($daysToCalving <= 14 ? 'warning' : 'success') }}">
                                            {{ $daysToCalving }} days
                                        </span>
                                    </div>
                                </td>
                                <td>{{ $record->date_of_service->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $record->breeding_method == 'AI' ? 'info' : ($record->breeding_method == 'Natural' ? 'success' : 'warning') }}">
                                        {{ $record->breeding_method }}
                                    </span>
                                </td>
                                <td>
                                    @if($record->pregnancy_diagnosis_date)
                                        {{ $record->pregnancy_diagnosis_date->format('M d') }}
                                    @else
                                        <span class="text-muted">Not recorded</span>
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
                                        <button type="button" class="btn btn-outline-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#recordCalvingModal{{ $record->id }}">
                                            <i class="fas fa-baby"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Calving Modal -->
                            <div class="modal fade" id="recordCalvingModal{{ $record->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Record Calving for {{ $record->animal->animal_id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('breeding-records.update', $record) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="animal_id" value="{{ $record->animal_id }}">
                                                <input type="hidden" name="date_of_service" value="{{ $record->date_of_service->format('Y-m-d') }}">
                                                <input type="hidden" name="breeding_method" value="{{ $record->breeding_method }}">
                                                <input type="hidden" name="pregnancy_result" value="1">
                                                
                                                <div class="mb-3">
                                                    <label for="actual_calving_date{{ $record->id }}" class="form-label">Calving Date</label>
                                                    <input type="date" 
                                                           class="form-control" 
                                                           id="actual_calving_date{{ $record->id }}" 
                                                           name="actual_calving_date" 
                                                           value="{{ today()->format('Y-m-d') }}"
                                                           required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="calving_outcome{{ $record->id }}" class="form-label">Calving Outcome</label>
                                                    <select class="form-select" 
                                                            id="calving_outcome{{ $record->id }}" 
                                                            name="calving_outcome" 
                                                            required>
                                                        <option value="">Select Outcome</option>
                                                        <option value="Live Calf">Live Calf</option>
                                                        <option value="Stillborn">Stillborn</option>
                                                        <option value="Twins">Twins</option>
                                                        <option value="Abortion">Abortion</option>
                                                        <option value="Retained Placenta">Retained Placenta</option>
                                                        <option value="Dystocia">Dystocia (Difficult Birth)</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Record Calving</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    <h4>No Animals Due for Calving</h4>
                    <p class="text-muted">No animals are due for calving in the next 30 days.</p>
                    <a href="{{ route('breeding-records.pregnant') }}" class="btn btn-warning">
                        <i class="fas fa-baby me-2"></i>View All Pregnant Animals
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Calendar Preview -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        Upcoming Calvings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $nextWeek = collect($breedingRecords->items())->filter(function($record) {
                                return $record->expected_calving_date && 
                                       $record->expected_calving_date->between(now(), now()->addDays(7));
                            });
                            
                            $nextTwoWeeks = collect($breedingRecords->items())->filter(function($record) {
                                return $record->expected_calving_date && 
                                       $record->expected_calving_date->between(now()->addDays(8), now()->addDays(14));
                            });
                            
                            $restOfMonth = collect($breedingRecords->items())->filter(function($record) {
                                return $record->expected_calving_date && 
                                       $record->expected_calving_date->between(now()->addDays(15), now()->addDays(30));
                            });
                        @endphp
                        
                        <div class="col-md-4">
                            <div class="card bg-danger text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">This Week</h5>
                                    <h1 class="display-4">{{ $nextWeek->count() }}</h1>
                                    <p class="card-text">Calvings due within 7 days</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-warning text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Next Week</h5>
                                    <h1 class="display-4">{{ $nextTwoWeeks->count() }}</h1>
                                    <p class="card-text">Calvings due in 8-14 days</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-info text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Rest of Month</h5>
                                    <h1 class="display-4">{{ $restOfMonth->count() }}</h1>
                                    <p class="card-text">Calvings due in 15-30 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection