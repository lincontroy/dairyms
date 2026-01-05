@extends('layouts.app')

@section('title', 'Active Health Issues - Dairy Farm Management')
@section('page-title', 'Active Health Issues')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('health-records.index') }}">Health Records</a>
    </li>
    <li class="breadcrumb-item active">Active Issues</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                    Animals Under Treatment
                </h5>
                <a href="{{ route('health-records.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>All Records
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($healthRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Animal</th>
                                <th>Diagnosis</th>
                                <th>Treatment Started</th>
                                <th>Treatment</th>
                                <th>Drug</th>
                                <th>Duration</th>
                                <th>Veterinarian</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($healthRecords as $record)
                            <tr>
                                <td>
                                    <a href="{{ route('animals.show', $record->animal_id) }}">
                                        <strong>{{ $record->animal->animal_id }}</strong>
                                        @if($record->animal->name)
                                            <br><small>{{ $record->animal->name }}</small>
                                        @endif
                                    </a>
                                </td>
                                <td>{{ Str::limit($record->diagnosis, 25) }}</td>
                                <td>{{ $record->date->format('M d, Y') }}</td>
                                <td>{{ Str::limit($record->treatment, 30) }}</td>
                                <td>{{ $record->drug_name ?? 'N/A' }}</td>
                                <td>{{ $record->duration ?? 'N/A' }}</td>
                                <td>{{ $record->veterinarian ?? 'N/A' }}</td>
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
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>No Active Health Issues</h4>
                    <p class="text-muted">All animals are healthy!</p>
                    <a href="{{ route('health-records.create') }}" class="btn btn-danger">
                        <i class="fas fa-plus me-2"></i>Add Health Record
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection