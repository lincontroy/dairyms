@extends('layouts.app')

@section('title', 'User Management - Dairy Farm Management')
@section('page-title', 'User Management')

@section('breadcrumbs')
    <li class="breadcrumb-item active">User Management</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Users</h6>
                            <h3 class="mb-0">{{ $users->total() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Managers</h6>
                            <h3 class="mb-0">{{ \App\Models\User::where('role', 'manager')->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-user-tie fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Veterinarians</h6>
                            <h3 class="mb-0">{{ \App\Models\User::where('role', 'vet')->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-stethoscope fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Staff</h6>
                            <h3 class="mb-0">{{ \App\Models\User::where('role', 'staff')->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-user fa-3x opacity-50"></i>
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
                        <h5 class="mb-0">User Management</h5>
                        <div>
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add New User
                            </a>
                            @if(auth()->user()->canApproveMilkRecords())
                                <a href="{{ route('users.pending-milk-approvals') }}" class="btn btn-warning ms-2">
                                    <i class="fas fa-check-circle me-2"></i>Pending Approvals
                                    @php
                                        $pendingCount = \App\Models\MilkProduction::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge bg-danger">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-users me-2"></i>
                System Users
            </h5>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_picture)
                                            <img src="{{ Storage::url($user->profile_picture) }}" 
                                                 alt="Profile" 
                                                 class="rounded-circle me-2"
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->id === auth()->id())
                                                <span class="badge bg-info">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleColors = [
                                            'admin' => 'danger',
                                            'manager' => 'success',
                                            'vet' => 'info',
                                            'staff' => 'warning'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('users.show', $user) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id() && auth()->user()->canManageUsers())
                                            <form action="{{ route('users.destroy', $user) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                                        class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4>No Users Found</h4>
                    <p class="text-muted">No users have been added to the system yet.</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add First User
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection