@extends('layouts.app')

@section('title', 'Pending Milk Approvals - Dairy Farm Management')
@section('page-title', 'Pending Milk Approvals')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">User Management</a>
    </li>
    <li class="breadcrumb-item active">Pending Approvals</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-check-circle me-2 text-warning"></i>
                    Pending Milk Record Approvals
                </h5>
                <div>
                    <span class="badge bg-warning">{{ $pendingRecords->total() }} pending</span>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Records Table -->
    <div class="card">
        <div class="card-body">
            @if($pendingRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Animal</th>
                                <th>Milker</th>
                                <th>Morning (L)</th>
                                <th>Evening (L)</th>
                                <th>Total (L)</th>
                                <th>Recorded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRecords as $record)
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
                                <td>
                                    {{ $record->milker->name }}
                                    <br>
                                    <small class="text-muted">{{ ucfirst($record->milker->role) }}</small>
                                </td>
                                <td>{{ number_format($record->morning_yield, 2) }}</td>
                                <td>{{ number_format($record->evening_yield, 2) }}</td>
                                <td>
                                    <strong>{{ number_format($record->total_yield, 2) }}</strong>
                                </td>
                                <td>
                                    {{ $record->created_at->format('M d, H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $record->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" 
                                                class="btn btn-success"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal{{ $record->id }}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $record->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $record->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Milk Record</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to approve this milk record?</p>
                                            <p><strong>Animal:</strong> {{ $record->animal->animal_id }}</p>
                                            <p><strong>Date:</strong> {{ $record->date->format('M d, Y') }}</p>
                                            <p><strong>Total Yield:</strong> {{ $record->total_yield }} L</p>
                                            <p><strong>Recorded by:</strong> {{ $record->milker->name }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('users.approve-milk-record', $record) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Approve Record</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $record->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Milk Record</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('users.reject-milk-record', $record) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Are you sure you want to reject this milk record?</p>
                                                <p><strong>Animal:</strong> {{ $record->animal->animal_id }}</p>
                                                <p><strong>Date:</strong> {{ $record->date->format('M d, Y') }}</p>
                                                <p><strong>Total Yield:</strong> {{ $record->total_yield }} L</p>
                                                
                                                <div class="mb-3">
                                                    <label for="rejection_reason{{ $record->id }}" class="form-label">
                                                        Reason for Rejection (Optional)
                                                    </label>
                                                    <textarea class="form-control" 
                                                              id="rejection_reason{{ $record->id }}" 
                                                              name="rejection_reason" 
                                                              rows="3"
                                                              placeholder="Provide reason for rejection..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Reject Record</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $pendingRecords->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>No Pending Approvals</h4>
                    <p class="text-muted">All milk records have been approved.</p>
                    <a href="{{ route('milk-production.index') }}" class="btn btn-primary">
                        <i class="fas fa-wine-bottle me-2"></i>View Milk Records
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-3x mb-3 opacity-50"></i>
                    <h3>{{ $pendingRecords->total() }}</h3>
                    <h6>Pending Approval</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x mb-3 opacity-50"></i>
                    <h3>
                        @php
                            $approvedToday = \App\Models\MilkProduction::where('status', 'approved')
                                ->whereDate('approved_at', today())
                                ->count();
                        @endphp
                        {{ $approvedToday }}
                    </h3>
                    <h6>Approved Today</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                    <h3>
                        @php
                            $staffWithPending = \App\Models\User::where('role', 'staff')
                                ->whereHas('milkProductions', function($query) {
                                    $query->where('status', 'pending');
                                })
                                ->count();
                        @endphp
                        {{ $staffWithPending }}
                    </h3>
                    <h6>Staff with Pending</h6>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus rejection reason textarea when reject modal opens
        const rejectModals = document.querySelectorAll('[id^="rejectModal"]');
        rejectModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function () {
                const textarea = this.querySelector('textarea');
                if (textarea) {
                    textarea.focus();
                }
            });
        });
    });
</script>
@endpush
@endsection