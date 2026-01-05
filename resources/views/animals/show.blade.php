@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Animal Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="card-title">
                                <i class="fas fa-cow text-success"></i> {{ $animal->name ?? 'Unnamed' }}
                                <span class="badge bg-secondary">{{ $animal->animal_id }}</span>
                            </h2>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <p><strong>Ear Tag:</strong> {{ $animal->ear_tag }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Breed:</strong> {{ $animal->breed }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Age:</strong> {{ \Carbon\Carbon::parse($animal->date_of_birth)->age }} years</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Status:</strong> 
                                        <span class="animal-status status-{{ strtolower($animal->status) }}">
                                            {{ $animal->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            @can('update', $animal)
                                <a href="{{ route('animals.edit', $animal) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endcan
                            @can('delete', $animal)
                                <form action="{{ route('animals.destroy', $animal) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this animal? This action cannot be undone.')" 
                                            class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="animalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="breeding-tab" data-bs-toggle="tab" 
                    data-bs-target="#breeding" type="button" role="tab">
                <i class="fas fa-dna"></i> Breeding History
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="milk-tab" data-bs-toggle="tab" 
                    data-bs-target="#milk" type="button" role="tab">
                <i class="fas fa-wine-bottle"></i> Milk Production
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="health-tab" data-bs-toggle="tab" 
                    data-bs-target="#health" type="button" role="tab">
                <i class="fas fa-heartbeat"></i> Health Records
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="animalTabsContent">
        <!-- Breeding Tab -->
        <div class="tab-pane fade show active" id="breeding" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Breeding Records</h5>
                </div>
                <div class="card-body">
                    @if($animal->breedingRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date of Service</th>
                                        <th>Method</th>
                                        <th>Bull/Semen ID</th>
                                        <th>Pregnancy Result</th>
                                        <th>Expected Calving</th>
                                        <th>Actual Calving</th>
                                        <th>Outcome</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($animal->breedingRecords->sortByDesc('date_of_service') as $record)
                                    <tr>
                                        <td>{{ $record->date_of_service->format('Y-m-d') }}</td>
                                        <td>{{ $record->breeding_method }}</td>
                                        <td>{{ $record->bull_semen_id }}</td>
                                        <td>
                                            @if($record->pregnancy_result === true)
                                                <span class="badge bg-success">Pregnant</span>
                                            @elseif($record->pregnancy_result === false)
                                                <span class="badge bg-danger">Not Pregnant</span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $record->expected_calving_date?->format('Y-m-d') ?? '-' }}</td>
                                        <td>{{ $record->actual_calving_date?->format('Y-m-d') ?? '-' }}</td>
                                        <td>{{ $record->calving_outcome ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No breeding records found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Milk Production Tab -->
        <div class="tab-pane fade" id="milk" role="tabpanel">
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Milk Production Chart</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="milkChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Milk Records</h5>
                        </div>
                        <div class="card-body">
                            @if($animal->milkProductions->count() > 0)
                                <div class="list-group">
                                    @foreach($animal->milkProductions->sortByDesc('date')->take(5) as $milk)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $milk->date->format('M d, Y') }}</h6>
                                            <strong>{{ $milk->total_yield }} L</strong>
                                        </div>
                                        <small class="text-muted">
                                            AM: {{ $milk->morning_yield }}L | PM: {{ $milk->evening_yield }}L
                                        </small>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No milk production records.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Tab -->
        <div class="tab-pane fade" id="health" role="tabpanel">
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Health & Treatment Records</h5>
                </div>
                <div class="card-body">
                    @if($animal->healthRecords->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Diagnosis</th>
                                        <th>Treatment</th>
                                        <th>Drug</th>
                                        <th>Withdrawal</th>
                                        <th>Vet</th>
                                        <th>Outcome</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($animal->healthRecords->sortByDesc('date') as $record)
                                    <tr>
                                        <td>{{ $record->date->format('Y-m-d') }}</td>
                                        <td>{{ $record->diagnosis }}</td>
                                        <td>{{ Str::limit($record->treatment, 30) }}</td>
                                        <td>{{ $record->drug_name }}</td>
                                        <td>
                                            <span class="badge bg-warning">Milk: {{ $record->milk_withdrawal_days }}d</span>
                                            <span class="badge bg-danger">Meat: {{ $record->meat_withdrawal_days }}d</span>
                                        </td>
                                        <td>{{ $record->veterinarian }}</td>
                                        <td>
                                            @if($record->outcome === 'Recovered')
                                                <span class="badge bg-success">Recovered</span>
                                            @elseif($record->outcome === 'Under Treatment')
                                                <span class="badge bg-warning">Under Treatment</span>
                                            @else
                                                <span class="badge bg-danger">{{ $record->outcome }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No health records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Milk Production Chart
    document.addEventListener('DOMContentLoaded', function() {
        const milkCtx = document.getElementById('milkChart');
        
        if (milkCtx) {
            // Get milk data from PHP and format it for Chart.js
            const milkData = {!! json_encode($animal->milkProductions->sortBy('date')->map(function($item) {
                return [
                    'date' => $item->date->format('M d'),
                    'total' => $item->total_yield,
                    'morning' => $item->morning_yield,
                    'evening' => $item->evening_yield
                ];
            })) !!};
            
            if (milkData.length > 0) {
                new Chart(milkCtx, {
                    type: 'line',
                    data: {
                        labels: milkData.map(item => item.date),
                        datasets: [
                            {
                                label: 'Total Yield',
                                data: milkData.map(item => item.total),
                                borderColor: '#4CAF50',
                                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Morning',
                                data: milkData.map(item => item.morning),
                                borderColor: '#2196F3',
                                borderDash: [5, 5],
                                fill: false
                            },
                            {
                                label: 'Evening',
                                data: milkData.map(item => item.evening),
                                borderColor: '#FF9800',
                                borderDash: [5, 5],
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Milk Production Trend'
                            },
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Liters'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            }
                        }
                    }
                });
            } else {
                // If no data, show a message
                milkCtx.style.display = 'none';
                const parent = milkCtx.parentElement;
                const message = document.createElement('div');
                message.className = 'text-center text-muted py-4';
                message.innerHTML = '<i class="fas fa-chart-line fa-3x mb-3"></i><h5>No milk production data available</h5><p>Start recording milk production to see the chart.</p>';
                parent.appendChild(message);
            }
        }
        
        // Initialize Bootstrap tabs
        const triggerTabList = [].slice.call(document.querySelectorAll('#animalTabs button'));
        triggerTabList.forEach(function (triggerEl) {
            const tabTrigger = new bootstrap.Tab(triggerEl);
            
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .animal-status {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 500;
    }
    
    .status-lactating {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-pregnant {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-dry {
        background-color: #e2e3e5;
        color: #383d41;
    }
    
    .status-heifer {
        background-color: #cce5ff;
        color: #004085;
    }
    
    .status-calf {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--farm-green);
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }
    
    .tab-content {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-top: none;
        padding: 1.5rem;
        border-radius: 0 0 0.25rem 0.25rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-title {
            font-size: 1.25rem;
        }
        
        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
        }
        
        .nav-tabs .nav-item {
            white-space: nowrap;
        }
    }
</style>
@endpush
@endsection