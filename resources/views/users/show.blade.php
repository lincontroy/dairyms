@extends('layouts.app')

@section('title', 'User Details - Dairy Farm Management')
@section('page-title', 'User Details')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">User Management</a>
    </li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- User Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" 
                             alt="Profile Picture" 
                             class="rounded-circle mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-4x text-muted"></i>
                        </div>
                    @endif
                    
                    <h4>{{ $user->name }}</h4>
                    
                    @php
                        $roleColors = [
                            'admin' => 'danger',
                            'manager' => 'success',
                            'vet' => 'info',
                            'staff' => 'warning'
                        ];
                    @endphp
                    <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }} fs-6 mb-2">
                        {{ ucfirst($user->role) }}
                    </span>
                    
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    @if($user->id === auth()->id())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This is your account
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        <a href="{{ route('users.activity-log', $user) }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Activity Log
                        </a>
                        @if($user->id !== auth()->id() && auth()->user()->canManageUsers())
                            <form action="{{ route('users.destroy', $user) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                        class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Delete User
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Permissions -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Permissions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Approve Milk Records</span>
                            <span class="badge bg-{{ $user->canApproveMilkRecords() ? 'success' : 'secondary' }}">
                                {{ $user->canApproveMilkRecords() ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Manage Health Records</span>
                            <span class="badge bg-{{ $user->canManageHealthRecords() ? 'success' : 'secondary' }}">
                                {{ $user->canManageHealthRecords() ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Manage Breeding Records</span>
                            <span class="badge bg-{{ $user->canManageBreedingRecords() ? 'success' : 'secondary' }}">
                                {{ $user->canManageBreedingRecords() ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>Manage Users</span>
                            <span class="badge bg-{{ $user->canManageUsers() ? 'success' : 'secondary' }}">
                                {{ $user->canManageUsers() ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- User Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-id-card me-2"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $user->phone ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $user->address ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Member Since:</th>
                                    <td>{{ $user->created_at->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Email Verified:</th>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Yes</span>
                                            ({{ $user->email_verified_at->format('M d, Y') }})
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $user->updated_at->format('F d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notification Preferences -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>
                        Notification Preferences
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="email_notifications" {{ $user->email_notifications ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="email_notifications">
                                    Email Notifications
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="sms_notifications" {{ $user->sms_notifications ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="sms_notifications">
                                    SMS Notifications
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="health_alerts" {{ $user->health_alerts ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="health_alerts">
                                    Health Alerts
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Recent Activity
                    </h5>
                    <a href="{{ route('users.activity-log', $user) }}" class="btn btn-sm btn-outline-primary">
                        View Full Log
                    </a>
                </div>
                <div class="card-body">
                    @php
                        // Get recent milk records
                        $recentMilk = $user->milkProductions()->latest()->take(5)->get();
                        // Get recent health records (if user has any)
                        $recentHealth = method_exists($user, 'healthRecords') ? 
                                       $user->healthRecords()->latest()->take(5)->get() : collect();
                    @endphp
                    
                    <h6>Recent Milk Records</h6>
                    @if($recentMilk->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Animal</th>
                                        <th>Total Yield</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMilk as $record)
                                    <tr>
                                        <td>{{ $record->date->format('M d') }}</td>
                                        <td>{{ $record->animal->animal_id ?? 'N/A' }}</td>
                                        <td>{{ $record->total_yield }} L</td>
                                        <td>
                                            @if($record->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($record->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No milk records found.</p>
                    @endif
                    
                    @if($recentHealth->count() > 0)
                        <h6 class="mt-4">Recent Health Records</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Animal</th>
                                        <th>Diagnosis</th>
                                        <th>Outcome</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentHealth as $record)
                                    <tr>
                                        <td>{{ $record->date->format('M d') }}</td>
                                        <td>{{ $record->animal->animal_id ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($record->diagnosis, 20) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $record->outcome == 'Recovered' ? 'success' : 'warning' }}">
                                                {{ $record->outcome }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection