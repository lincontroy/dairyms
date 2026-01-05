<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h2 class="text-primary">{{ $data['summary']['total_services'] }}</h2>
                <small class="text-muted">Total Services</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h2 class="text-success">{{ $data['summary']['successful_pregnancies'] }}</h2>
                <small class="text-muted">Successful</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h2 class="text-warning">{{ $data['summary']['current_pregnancies'] }}</h2>
                <small class="text-muted">Current Pregnancies</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <h2 class="text-info">{{ number_format($data['summary']['success_rate'], 1) }}%</h2>
                <small class="text-muted">Success Rate</small>
            </div>
        </div>
    </div>
</div>

@if($data['current_pregnancies']->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-baby me-2 text-warning"></i>
            Current Pregnancies
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Animal</th>
                        <th>Service Date</th>
                        <th>Days Pregnant</th>
                        <th>Expected Calving</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['current_pregnancies'] as $record)
                    <tr>
                        <td>
                            <strong>{{ $record->animal->animal_id }}</strong>
                            @if($record->animal->name)
                                <br><small>{{ $record->animal->name }}</small>
                            @endif
                        </td>
                        <td>{{ $record->date_of_service->format('M d, Y') }}</td>
                        <td>
                            {{ $record->days_pregnant ?? 'N/A' }}
                            @if($record->days_pregnant >= 270)
                                <span class="badge bg-danger ms-2">Due Soon</span>
                            @endif
                        </td>
                        <td>
                            @if($record->expected_calving_date)
                                {{ $record->expected_calving_date->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $record->breeding_method }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-dna me-2"></i>
            All Breeding Records
        </h5>
    </div>
    <div class="card-body">
        @if($data['records']->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Service Date</th>
                        <th>Animal</th>
                        <th>Method</th>
                        <th>Pregnancy Result</th>
                        <th>Calving Date</th>
                        <th>Outcome</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['records']->sortByDesc('date_of_service') as $record)
                    <tr>
                        <td>{{ $record->date_of_service->format('M d, Y') }}</td>
                        <td>{{ $record->animal->animal_id }}</td>
                        <td>{{ $record->breeding_method }}</td>
                        <td>
                            @if($record->pregnancy_result)
                                <span class="badge bg-success">Successful</span>
                            @else
                                <span class="badge bg-secondary">Unsuccessful</span>
                            @endif
                        </td>
                        <td>
                            @if($record->actual_calving_date)
                                {{ $record->actual_calving_date->format('M d, Y') }}
                            @else
                                @if($record->pregnancy_result)
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    N/A
                                @endif
                            @endif
                        </td>
                        <td>{{ $record->calving_outcome ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-dna fa-3x text-muted mb-3"></i>
            <p class="text-muted">No breeding records found</p>
        </div>
        @endif
    </div>
</div>