<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2 text-success"></i>
            Report Summary
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <h2 class="text-success">{{ $data['summary']['total'] }}</h2>
                    <small class="text-muted">Total Animals</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <h2 class="text-primary">{{ $data['summary']['active'] }}</h2>
                    <small class="text-muted">Active Animals</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <h2 class="text-info">{{ count($data['summary']['by_breed']) }}</h2>
                    <small class="text-muted">Breeds</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="text-center">
                    <h2 class="text-warning">{{ count($data['summary']['by_status']) }}</h2>
                    <small class="text-muted">Status Types</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-cow me-2 text-success"></i>
            Animal Details
        </h5>
        <span class="badge bg-success">{{ $data['animals']->count() }} records</span>
    </div>
    <div class="card-body">
        @if($data['animals']->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Animal ID</th>
                        <th>Name</th>
                        <th>Breed</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Age</th>
                        <th>Dam</th>
                        <th>Sire</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['animals'] as $animal)
                    <tr>
                        <td><strong>{{ $animal->animal_id }}</strong></td>
                        <td>{{ $animal->name ?? 'N/A' }}</td>
                        <td>{{ $animal->breed }}</td>
                        <td>
                            <span class="badge 
                                @if($animal->status == 'lactating') bg-success
                                @elseif($animal->status == 'pregnant') bg-warning
                                @elseif($animal->status == 'heifer') bg-info
                                @elseif($animal->status == 'calf') bg-primary
                                @else bg-secondary @endif">
                                {{ ucfirst($animal->status) }}
                            </span>
                        </td>
                        <td>{{ $animal->date_added->format('M d, Y') }}</td>
                        <td>
                            {{ $animal->date_of_birth ? $animal->date_of_birth->diffInYears() . ' years' : 'N/A' }}
                        </td>
                        <td>{{ $animal->dam->animal_id ?? 'N/A' }}</td>
                        <td>{{ $animal->sire->animal_id ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5>No animals found</h5>
            <p class="text-muted">Try adjusting your filters</p>
        </div>
        @endif
    </div>
</div>