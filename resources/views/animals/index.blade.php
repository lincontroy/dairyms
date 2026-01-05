@extends('layouts.app')

@section('title', 'Animal Registry - Dairy Farm Management')
@section('page-title', 'Animal Registry')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="fas fa-cow text-success me-2"></i>Animal Registry
                    </h4>
                    <p class="text-muted mb-0">Manage all animals in your dairy farm</p>
                </div>
                <div>
                    <a href="{{ route('animals.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>New Animal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('animals.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="calf" {{ request('status') == 'calf' ? 'selected' : '' }}>Calf</option>
                        <option value="heifer" {{ request('status') == 'heifer' ? 'selected' : '' }}>Heifer</option>
                        <option value="lactating" {{ request('status') == 'lactating' ? 'selected' : '' }}>Lactating</option>
                        <option value="dry" {{ request('status') == 'dry' ? 'selected' : '' }}>Dry</option>
                        <option value="pregnant" {{ request('status') == 'pregnant' ? 'selected' : '' }}>Pregnant</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="dead" {{ request('status') == 'dead' ? 'selected' : '' }}>Dead</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Breed</label>
                    <select class="form-select" name="breed">
                        <option value="">All Breeds</option>
                        @foreach(['Holstein Friesian', 'Jersey', 'Guernsey', 'Ayrshire', 'Brown Swiss', 'Crossbreed', 'Other'] as $breed)
                            <option value="{{ $breed }}" {{ request('breed') == $breed ? 'selected' : '' }}>
                                {{ $breed }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Sex</label>
                    <select class="form-select" name="sex">
                        <option value="">All</option>
                        <option value="Female" {{ request('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Male" {{ request('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="ID, name, ear tag..." value="{{ request('search') }}">
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ $animals->total() }} animals found
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('animals.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Animals Table -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list text-success me-2"></i>Animals
                    <span class="badge bg-success">{{ $animals->total() }}</span>
                </h5>
                <div>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">CSV</a></li>
                            <li><a class="dropdown-item" href="#">Excel</a></li>
                            <li><a class="dropdown-item" href="#">PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($animals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Animal ID</th>
                                <th>Name</th>
                                <th>Ear Tag</th>
                                <th>Breed</th>
                                <th>Sex</th>
                                <th>Age</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($animals as $animal)
                            <tr>
                                <td>
                                    <strong>{{ $animal->animal_id }}</strong>
                                    @if(!$animal->is_active)
                                        <span class="badge bg-danger ms-1">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $animal->name ?? 'Unnamed' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $animal->ear_tag }}</span>
                                </td>
                                <td>{{ $animal->breed }}</td>
                                <td>
                                    <span class="badge bg-{{ $animal->sex == 'Female' ? 'danger' : 'primary' }}">
                                        {{ $animal->sex }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($animal->date_of_birth)->diffInYears() }} years
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'calf' => 'info',
                                            'heifer' => 'primary',
                                            'lactating' => 'success',
                                            'dry' => 'warning',
                                            'pregnant' => 'danger',
                                            'sold' => 'secondary',
                                            'dead' => 'dark'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$animal->status] ?? 'secondary' }}">
                                        {{ $animal->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('animals.show', $animal) }}" 
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('animals.edit', $animal) }}" 
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                        <form action="{{ route('animals.destroy', $animal) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete {{ $animal->animal_id }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
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
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <p class="text-muted mb-0">
                            Showing {{ $animals->firstItem() }} to {{ $animals->lastItem() }} of {{ $animals->total() }} entries
                        </p>
                    </div>
                    <div>
                        {{ $animals->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-cow fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">No Animals Found</h4>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['status', 'breed', 'sex', 'search']))
                            No animals match your search criteria.
                        @else
                            Your animal registry is empty.
                        @endif
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('animals.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Register Animal
                        </a>
                        @if(request()->hasAny(['status', 'breed', 'sex', 'search']))
                            <a href="{{ route('animals.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie text-success me-2"></i>Animal Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 mb-1">{{ $animals->where('status', 'lactating')->count() }}</div>
                            <small class="text-muted">Lactating</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-1">{{ $animals->where('status', 'dry')->count() }}</div>
                            <small class="text-muted">Dry</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-1">{{ $animals->where('status', 'calf')->count() }}</div>
                            <small class="text-muted">Calves</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle text-success me-2"></i>Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted">Active Animals</small>
                                <div class="h5 mb-0">{{ $animals->where('is_active', true)->count() }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted">Females</small>
                                <div class="h5 mb-0">{{ $animals->where('sex', 'Female')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this animal?</p>
                <p class="text-muted small">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete modal functionality
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteForm = document.getElementById('deleteForm');
        
        // Attach delete handlers to all delete buttons
        document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const animalId = this.closest('form').action.split('/').pop();
                    const animalName = this.closest('tr').querySelector('td strong').textContent;
                    
                    // Update modal content
                    document.querySelector('#deleteModal .modal-body p:first-child').innerHTML = 
                        `Are you sure you want to delete <strong>${animalName}</strong>?`;
                    
                    // Set form action
                    deleteForm.action = `/animals/${animalId}`;
                    
                    // Show modal
                    deleteModal.show();
                });
            }
        });
        
        // Handle modal delete button
        deleteForm.querySelector('button[type="submit"]').addEventListener('click', function() {
            // Submit the form
            deleteForm.submit();
        });
        
        // Quick search on Enter key
        document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
        
        // Status badge colors
        const statusBadges = document.querySelectorAll('.badge.bg-');
        statusBadges.forEach(badge => {
            const status = badge.textContent.trim().toLowerCase();
            const colors = {
                'calf': 'info',
                'heifer': 'primary',
                'lactating': 'success',
                'dry': 'warning',
                'pregnant': 'danger',
                'sold': 'secondary',
                'dead': 'dark'
            };
            
            if (colors[status]) {
                badge.className = `badge bg-${colors[status]}`;
            }
        });
    });
</script>
@endpush