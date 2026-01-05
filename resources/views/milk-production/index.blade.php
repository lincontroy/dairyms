@extends('layouts.app')

@section('title', 'Milk Production - Dairy Farm Management')
@section('page-title', 'Milk Production')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Milk Production</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Milk</h6>
                            <h3 class="mb-0">{{ number_format($todayTotal, 2) }} L</h3>
                        </div>
                        <div>
                            <i class="fas fa-wine-bottle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">This Month</h6>
                            <h3 class="mb-0">{{ number_format($monthTotal, 2) }} L</h3>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
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
                            <h6 class="text-white-50 mb-1">Avg Per Day</h6>
                            <h3 class="mb-0">
                                {{ $todayTotal > 0 ? number_format($todayTotal / max(1, $milkProductions->where('date', today())->count()), 2) : '0.00' }} L
                            </h3>
                        </div>
                        <div>
                            <i class="fas fa-calculator fa-3x opacity-50"></i>
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
                        <h5 class="mb-0">Milk Production Management</h5>
                        <div>
                            <a href="{{ route('milk-production.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add Single Record
                            </a>
                            <a href="{{ route('milk-production.quick-entry') }}" class="btn btn-primary ms-2">
                                <i class="fas fa-bolt me-2"></i>Quick Entry
                            </a>
                            <a href="{{ route('milk-production.monthly-report') }}" class="btn btn-info ms-2">
                                <i class="fas fa-chart-bar me-2"></i>Monthly Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Milk Production Records -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Recent Milk Production Records
            </h5>
        </div>
        <div class="card-body">
            @if($milkProductions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Animal</th>
                                <th>Morning (L)</th>
                                <th>Evening (L)</th>
                                <th>Total (L)</th>
                                <th>Milker</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($milkProductions as $record)
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
                                <td>{{ number_format($record->morning_yield, 2) }}</td>
                                <td>{{ number_format($record->evening_yield, 2) }}</td>
                                <td>
                                    <strong>{{ number_format($record->total_yield, 2) }}</strong>
                                </td>
                                <td>{{ $record->milker->name ?? 'N/A' }}</td>

                                <!-- In the table body -->
<td>
    @if($record->status === 'approved')
        <span class="badge bg-success">Approved</span>
        <br>
        <small class="text-muted">
            by {{ $record->approver->name ?? 'N/A' }}
        </small>
    @elseif($record->status === 'pending')
        <span class="badge bg-warning">Pending</span>
        @if(auth()->user()->canApproveMilkRecords())
            <br>
            <a href="{{ route('users.pending-milk-approvals') }}" class="btn btn-sm btn-outline-success mt-1">
                <i class="fas fa-check"></i> Approve
            </a>
        @endif
    @else
        <span class="badge bg-danger">Rejected</span>
    @endif
</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('milk-production.show', $record) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('milk-production.edit', $record) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('milk-production.destroy', $record) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this milk record?')"
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
                    {{ $milkProductions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-wine-bottle fa-4x text-muted mb-3"></i>
                    <h4>No Milk Production Records</h4>
                    <p class="text-muted">Start recording milk production to see data here.</p>
                    <a href="{{ route('milk-production.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add First Record
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection