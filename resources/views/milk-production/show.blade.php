@extends('layouts.app')

@section('title', 'Milk Production Record - Dairy Farm Management')
@section('page-title', 'Milk Production Record')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-production.index') }}">Milk Production</a>
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
                        <i class="fas fa-wine-bottle me-2 text-success"></i>
                        Milk Production Details
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('milk-production.edit', $milkProduction) }}" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('milk-production.destroy', $milkProduction) }}" 
                              method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this record?')"
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
                                    <td>{{ $milkProduction->date->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Animal:</th>
                                    <td>
                                        <a href="{{ route('animals.show', $milkProduction->animal_id) }}">
                                            <strong>{{ $milkProduction->animal->animal_id }}</strong>
                                            @if($milkProduction->animal->name)
                                                - {{ $milkProduction->animal->name }}
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Breed:</th>
                                    <td>{{ $milkProduction->animal->breed }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-success">{{ $milkProduction->animal->status }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Morning Yield:</th>
                                    <td>{{ number_format($milkProduction->morning_yield, 2) }} L</td>
                                </tr>
                                <tr>
                                    <th>Evening Yield:</th>
                                    <td>{{ number_format($milkProduction->evening_yield, 2) }} L</td>
                                </tr>
                                <tr>
                                    <th>Total Yield:</th>
                                    <td>
                                        <strong>{{ number_format($milkProduction->total_yield, 2) }} L</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Recorded by:</th>
                                    <td>{{ $milkProduction->milker->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th>Lactation Number:</th>
                                            <td>{{ $milkProduction->lactation_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Days in Milk:</th>
                                            <td>{{ $milkProduction->days_in_milk ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created:</th>
                                            <td>{{ $milkProduction->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $milkProduction->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Notes</h6>
                                </div>
                                <div class="card-body">
                                    @if($milkProduction->notes)
                                        <p>{{ $milkProduction->notes }}</p>
                                    @else
                                        <p class="text-muted fst-italic">No notes recorded.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
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
                        @if($milkProduction->animal->photo)
                            <img src="{{ Storage::url($milkProduction->animal->photo) }}" 
                                 alt="Animal Photo" 
                                 class="img-thumbnail mb-2"
                                 style="max-width: 150px;">
                        @else
                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2"
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-cow fa-3x text-muted"></i>
                            </div>
                        @endif
                        <h5>{{ $milkProduction->animal->name ?? 'Unnamed' }}</h5>
                        <p class="text-muted mb-0">{{ $milkProduction->animal->animal_id }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Breed:</span>
                            <span>{{ $milkProduction->animal->breed }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Age:</span>
                            <span>{{ \Carbon\Carbon::parse($milkProduction->animal->date_of_birth)->age }} years</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-success">{{ $milkProduction->animal->status }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Added:</span>
                            <span>{{ $milkProduction->animal->date_added->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('animals.show', $milkProduction->animal_id) }}" 
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
                        <a href="{{ route('milk-production.create', ['animal_id' => $milkProduction->animal_id]) }}" 
                           class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i>New Record for Same Animal
                        </a>
                        <a href="{{ route('milk-production.create') }}" class="btn btn-primary">
                            <i class="fas fa-wine-bottle me-2"></i>New Milk Record
                        </a>
                        <a href="{{ route('milk-production.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>All Records
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection