<div class="row mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2 text-info"></i>
                    Production Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-primary">{{ number_format($data['summary']['total_milk'], 1) }}</h2>
                            <small class="text-muted">Total Milk (L)</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-success">{{ $data['summary']['total_records'] }}</h2>
                            <small class="text-muted">Records</small>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-3">
                        <div class="text-center">
                            <h2 class="text-info">{{ number_format($data['summary']['average_daily'], 1) }}</h2>
                            <small class="text-muted">Avg Daily (L)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-check-circle me-2 text-success"></i>
                    Record Status
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Approved</span>
                    <span class="badge bg-success">{{ $data['summary']['approved_records'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Pending</span>
                    <span class="badge bg-warning">{{ $data['summary']['pending_records'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-table me-2"></i>
            Daily Production
        </h5>
    </div>
    <div class="card-body">
        @if($data['daily_totals']->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Morning (L)</th>
                        <th>Evening (L)</th>
                        <th>Total (L)</th>
                        <th>Records</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['daily_totals']->sortByDesc('date') as $date => $totals)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                        <td>{{ number_format($totals['morning'], 1) }}</td>
                        <td>{{ number_format($totals['evening'], 1) }}</td>
                        <td><strong>{{ number_format($totals['total'], 1) }}</strong></td>
                        <td>{{ $totals['count'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-wine-bottle fa-3x text-muted mb-3"></i>
            <p class="text-muted">No milk production records found</p>
        </div>
        @endif
    </div>
</div>