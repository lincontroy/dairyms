@extends('layouts.app')

@section('title', 'Calves Management - Dairy Farm')
@section('page-title', 'Calves Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Calves</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-baby text-primary me-2"></i>
                                Calves Registry
                            </h5>
                            <p class="text-muted mb-0">Manage all calves born on the farm</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('calves.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Record New Calf
                            </a>
                            <a href="{{ route('calves.statistics') }}" class="btn btn-info ms-2">
                                <i class="fas fa-chart-bar me-1"></i> Statistics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('calves.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Calf ID, Ear Tag, Name...">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="alive" {{ request('status') == 'alive' ? 'selected' : '' }}>Alive</option>
                                <option value="dead" {{ request('status') == 'dead' ? 'selected' : '' }}>Dead</option>
                                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sex" class="form-label">Sex</label>
                            <select class="form-select" id="sex" name="sex">
                                <option value="">All Sex</option>
                                <option value="male" {{ request('sex') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('sex') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dam_id" class="form-label">Dam (Mother)</label>
                            <select class="form-select" id="dam_id" name="dam_id">
                                <option value="">All Dams</option>
                                @foreach($dams as $dam)
                                <option value="{{ $dam->id }}" {{ request('dam_id') == $dam->id ? 'selected' : '' }}>
                                    {{ $dam->name }} ({{ $dam->ear_tag }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="is_weaned" class="form-label">Weaned</label>
                            <select class="form-select" id="is_weaned" name="is_weaned">
                                <option value="">All</option>
                                <option value="1" {{ request('is_weaned') == '1' ? 'selected' : '' }}>Weaned</option>
                                <option value="0" {{ request('is_weaned') == '0' ? 'selected' : '' }}>Not Weaned</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('calves.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Calves</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $calves->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-baby fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Alive Calves</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $calves->where('status', 'alive')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Not Weaned</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $calves->where('is_weaned', false)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-milk-bottle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-danger border-4 h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                Special Care</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                {{ $calves->where('requires_special_care', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-medkit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calves Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Calf ID</th>
                                    <th>Ear Tag</th>
                                    <th>Name</th>
                                    <th>Sex</th>
                                    <th>Dam</th>
                                    <th>Date of Birth</th>
                                    <th>Status</th>
                                    <th>Health</th>
                                    <th>Weaned</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($calves as $calf)
                                <tr>
                                    <td>
                                        <strong>{{ $calf->calf_id }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $calf->ear_tag }}</span>
                                    </td>
                                    <td>
                                        {{ $calf->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @if($calf->sex == 'male')
                                        <span class="badge bg-info">Male</span>
                                        @else
                                        <span class="badge bg-pink">Female</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($calf->dam)
                                        <a href="{{ route('animals.show', $calf->dam) }}" class="text-decoration-none">
                                            {{ $calf->dam->name }} ({{ $calf->dam->ear_tag }})
                                        </a>
                                        @else
                                        <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $calf->date_of_birth->format('M d, Y') }}
                                        <small class="d-block text-muted">
                                            {{ $calf->age_in_days }} days old
                                        </small>
                                    </td>
                                    <td>
                                        @switch($calf->status)
                                            @case('alive')
                                                <span class="badge bg-success">Alive</span>
                                                @break
                                            @case('dead')
                                                <span class="badge bg-danger">Dead</span>
                                                @break
                                            @case('sold')
                                                <span class="badge bg-warning">Sold</span>
                                                @break
                                            @case('transferred')
                                                <span class="badge bg-info">Transferred</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($calf->health_status)
                                            @case('excellent')
                                                <span class="badge bg-success">Excellent</span>
                                                @break
                                            @case('good')
                                                <span class="badge bg-info">Good</span>
                                                @break
                                            @case('fair')
                                                <span class="badge bg-warning">Fair</span>
                                                @break
                                            @case('poor')
                                                <span class="badge bg-danger">Poor</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($calf->is_weaned)
                                        <span class="badge bg-success">Yes</span>
                                        @else
                                        <span class="badge bg-warning">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('calves.show', $calf) }}" 
                                               class="btn btn-primary" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('calves.edit', $calf) }}" 
                                               class="btn btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin())
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    title="Delete"
                                                    onclick="if(confirm('Delete this calf?')) { document.getElementById('delete-form-{{ $calf->id }}').submit(); }">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $calf->id }}" 
                                                  action="{{ route('calves.destroy', $calf) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-baby fa-3x mb-3"></i>
                                            <h5>No calves found</h5>
                                            <p>No calves have been recorded yet.</p>
                                            <a href="{{ route('calves.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus me-1"></i> Record First Calf
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($calves->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $calves->firstItem() }} to {{ $calves->lastItem() }} of {{ $calves->total() }} calves
                        </div>
                        <div>
                            {{ $calves->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge.bg-pink {
        background-color: #e83e8c;
        color: white;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(46, 125, 50, 0.05);
    }
</style>
@endpush