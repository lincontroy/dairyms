@extends('layouts.app')

@section('title', 'Health Records - Dairy Farm Management')
@section('page-title', 'Health Records')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Health Records</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Active Issues</h6>
                            <h3 class="mb-0">{{ $activeIssues }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-heartbeat fa-3x opacity-50"></i>
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
                            <h6 class="text-white-50 mb-1">Recovered This Month</h6>
                            <h3 class="mb-0">{{ $recoveredThisMonth }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
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
                            <h6 class="text-white-50 mb-1">Total Records</h6>
                            <h3 class="mb-0">{{ $healthRecords->total() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-file-medical fa-3x opacity-50"></i>
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
                        <h5 class="mb-0">Health Records Management</h5>
                        <div>
                            <a href="{{ route('health-records.create') }}" class="btn btn-danger">
                                <i class="fas fa-plus me-2"></i>New Health Record
                            </a>
                            <a href="{{ route('health-records.active') }}" class="btn btn-warning ms-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Active Issues
                            </a>
                            <a href="{{ route('health-records.monthly-report') }}" class="btn btn-info ms-2">
                                <i class="fas fa-chart-bar me-2"></i>Monthly Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Records Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-file-medical me-2"></i>
                Recent Health Records
            </h5>
        </div>
        <div class="card-body">
            @if($healthRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Animal</th>
                                <th>Diagnosis</th>
                                <th>Treatment</th>
                                <th>Veterinarian</th>
                                <th>Outcome</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($healthRecords as $record)
                            <tr>
                                <td>{{ $record->date->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('animals.show', $record->animal_id) }}">
                                        <strong>{{ $record->animal->animal_id }}</strong>
                                        @if($record->animal->name)
                                            <br><small>{{ $record->animal->name }}</small>
                                        @endif
                                    </a>
                                </td>
                                <td>{{ Str::limit($record->diagnosis, 30) }}</td>
                                <td>{{ Str::limit($record->treatment, 30) }}</td>
                                <td>{{ $record->veterinarian ?? 'N/A' }}</td>
                                <td>
                                    @if($record->outcome === 'Recovered')
                                        <span class="badge bg-success">Recovered</span>
                                    @elseif($record->outcome === 'Under Treatment')
                                        <span class="badge bg-warning">Under Treatment</span>
                                    @elseif($record->outcome === 'Not Responding')
                                        <span class="badge bg-danger">Not Responding</span>
                                    @elseif($record->outcome === 'Died')
                                        <span class="badge bg-dark">Died</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('health-records.show', $record) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('health-records.edit', $record) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('health-records.destroy', $record) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this health record?')"
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
                    {{ $healthRecords->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-heartbeat fa-4x text-muted mb-3"></i>
                    <h4>No Health Records</h4>
                    <p class="text-muted">Start recording animal health issues to see data here.</p>
                    <a href="{{ route('health-records.create') }}" class="btn btn-danger">
                        <i class="fas fa-plus me-2"></i>Add First Record
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection