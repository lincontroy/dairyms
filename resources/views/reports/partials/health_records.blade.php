<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-heartbeat me-2 text-danger"></i>
                    Health Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-danger">{{ $data['summary']['total_records'] }}</h2>
                            <small class="text-muted">Total Records</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-warning">{{ $data['summary']['under_treatment'] }}</h2>
                            <small class="text-muted">Under Treatment</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-stethoscope me-2 text-primary"></i>
                    Common Diagnoses
                </h5>
            </div>
            <div class="card-body">
                @if(count($data['summary']['by_diagnosis']) > 0)
                    @foreach($data['summary']['by_diagnosis']->sortDesc()->take(5) as $diagnosis => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-truncate" style="max-width: 70%;">{{ $diagnosis }}</span>
                        <span class="badge bg-primary">{{ $count }}</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">No diagnosis data</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-medical me-2"></i>
            Health Records
        </h5>
    </div>
    <div class="card-body">
        @if($data['records']->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Animal</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Veterinarian</th>
                        <th>Outcome</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['records']->sortByDesc('date') as $record)
                    <tr>
                        <td>{{ $record->date->format('M d, Y') }}</td>
                        <td>
                            <strong>{{ $record->animal->animal_id }}</strong>
                            @if($record->animal->name)
                                <br><small>{{ $record->animal->name }}</small>
                            @endif
                        </td>
                        <td>{{ $record->diagnosis }}</td>
                        <td>{{ $record->treatment ?? 'N/A' }}</td>
                        <td>{{ $record->veterinarian ?? 'N/A' }}</td>
                        <td>
                            <span class="badge 
                                @if($record->outcome == 'Recovered') bg-success
                                @elseif($record->outcome == 'Under Treatment') bg-warning
                                @elseif($record->outcome == 'Chronic') bg-info
                                @else bg-danger @endif">
                                {{ $record->outcome }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h5>No health issues found</h5>
            <p class="text-muted">All animals are healthy for this period</p>
        </div>
        @endif
    </div>
</div>