@extends('layouts.app')

@section('title', 'Breeding Calendar - Dairy Farm Management')
@section('page-title', 'Breeding Calendar')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('breeding-records.index') }}">Breeding Records</a>
    </li>
    <li class="breadcrumb-item active">Calendar</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Month Selection -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('breeding-records.calendar') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="month" class="form-label">Select Month</label>
                    <input type="month" 
                           class="form-control" 
                           id="month" 
                           name="month" 
                           value="{{ $month }}"
                           max="{{ now()->addMonths(6)->format('Y-m') }}"
                           min="{{ now()->subMonths(6)->format('Y-m') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('breeding-records.calendar', ['month' => now()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary">
                        Current Month
                    </a>
                    <a href="{{ route('breeding-records.calendar', ['month' => now()->addMonth()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary ms-2">
                        Next Month
                    </a>
                    <a href="{{ route('breeding-records.calendar', ['month' => now()->subMonth()->format('Y-m')]) }}" 
                       class="btn btn-outline-secondary ms-2">
                        Previous Month
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Legend -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">Calendar Legend</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">
                            <i class="fas fa-bullhorn me-1"></i>Breeding Date
                        </span>
                        <span class="badge bg-success">
                            <i class="fas fa-baby me-1"></i>Expected Calving
                        </span>
                        <span class="badge bg-info">
                            <i class="fas fa-stethoscope me-1"></i>Pregnancy Check
                        </span>
                        <span class="badge bg-warning">
                            <i class="fas fa-calendar-check me-1"></i>Actual Calving
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Breeding Calendar - {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
            </h5>
        </div>
        <div class="card-body">
            @php
                $date = \Carbon\Carbon::createFromFormat('Y-m', $month);
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();
                $startDate = $startOfMonth->copy()->startOfWeek();
                $endDate = $endOfMonth->copy()->endOfWeek();
                
                // Create calendar grid
                $calendar = [];
                $currentDate = $startDate->copy();
                
                while ($currentDate <= $endDate) {
                    $calendar[$currentDate->format('Y-m-d')] = [
                        'date' => $currentDate->copy(),
                        'events' => []
                    ];
                    $currentDate->addDay();
                }
                
                // Add events to calendar
                foreach ($breedingRecords as $category => $records) {
                    foreach ($records as $record) {
                        $eventDate = null;
                        $eventType = '';
                        $eventColor = '';
                        
                        if ($category === 'breeding') {
                            $eventDate = $record->date_of_service;
                            $eventType = 'breeding';
                            $eventColor = 'primary';
                        } elseif ($category === 'expected_calving') {
                            $eventDate = $record->expected_calving_date;
                            $eventType = 'expected_calving';
                            $eventColor = 'success';
                        } elseif ($category === 'actual_calving') {
                            $eventDate = $record->actual_calving_date;
                            $eventType = 'actual_calving';
                            $eventColor = 'warning';
                        } elseif ($category === 'pregnancy_check') {
                            $eventDate = $record->pregnancy_diagnosis_date;
                            $eventType = 'pregnancy_check';
                            $eventColor = 'info';
                        }
                        
                        if ($eventDate && isset($calendar[$eventDate->format('Y-m-d')])) {
                            $calendar[$eventDate->format('Y-m-d')]['events'][] = [
                                'record' => $record,
                                'type' => $eventType,
                                'color' => $eventColor
                            ];
                        }
                    }
                }
            @endphp
            
            <!-- Calendar Header -->
            <div class="row mb-3">
                <div class="col text-center">
                    <button class="btn btn-outline-primary" onclick="changeMonth(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="mx-3 h4">{{ $date->format('F Y') }}</span>
                    <button class="btn btn-outline-primary" onclick="changeMonth(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="calendar-grid">
                <!-- Weekday headers -->
                <div class="row g-1 mb-2">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="col text-center fw-bold text-muted">
                        {{ $day }}
                    </div>
                    @endforeach
                </div>
                
                <!-- Calendar days -->
                @foreach(array_chunk($calendar, 7, true) as $week)
                <div class="row g-1 mb-1">
                    @foreach($week as $dateStr => $dayData)
                    @php
                        $isCurrentMonth = $dayData['date']->format('Y-m') == $month;
                        $isToday = $dayData['date']->isToday();
                        $eventCount = count($dayData['events']);
                    @endphp
                    <div class="col calendar-day {{ !$isCurrentMonth ? 'text-muted' : '' }} {{ $isToday ? 'today' : '' }}">
                        <div class="calendar-day-header">
                            {{ $dayData['date']->format('j') }}
                        </div>
                        <div class="calendar-day-content">
                            @if($eventCount > 0)
                                <div class="calendar-events">
                                    @foreach(array_slice($dayData['events'], 0, 3) as $event)
                                    <div class="calendar-event mb-1">
                                        <a href="{{ route('breeding-records.show', $event['record']) }}" 
                                           class="text-decoration-none d-block"
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top"
                                           title="{{ $event['record']->animal->animal_id }} - {{ $event['type'] == 'breeding' ? 'Breeding' : ($event['type'] == 'expected_calving' ? 'Expected Calving' : ($event['type'] == 'actual_calving' ? 'Calved' : 'Pregnancy Check')) }}">
                                            <span class="badge bg-{{ $event['color'] }} w-100 text-start text-truncate">
                                                <i class="fas fa-{{ $event['type'] == 'breeding' ? 'bullhorn' : ($event['type'] == 'expected_calving' ? 'baby' : ($event['type'] == 'actual_calving' ? 'calendar-check' : 'stethoscope')) }} me-1"></i>
                                                {{ $event['record']->animal->animal_id }}
                                            </span>
                                        </a>
                                    </div>
                                    @endforeach
                                    @if($eventCount > 3)
                                        <div class="text-center">
                                            <small class="text-muted">+{{ $eventCount - 3 }} more</small>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <small class="text-muted">No events</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Events Summary -->
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-bullhorn fa-3x mb-3 opacity-50"></i>
                    <h3>{{ isset($breedingRecords['breeding']) ? count($breedingRecords['breeding']) : 0 }}</h3>
                    <h6>Breeding Events</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-baby fa-3x mb-3 opacity-50"></i>
                    <h3>{{ isset($breedingRecords['expected_calving']) ? count($breedingRecords['expected_calving']) : 0 }}</h3>
                    <h6>Expected Calvings</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-stethoscope fa-3x mb-3 opacity-50"></i>
                    <h3>{{ isset($breedingRecords['pregnancy_check']) ? count($breedingRecords['pregnancy_check']) : 0 }}</h3>
                    <h6>Pregnancy Checks</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-3x mb-3 opacity-50"></i>
                    <h3>{{ isset($breedingRecords['actual_calving']) ? count($breedingRecords['actual_calving']) : 0 }}</h3>
                    <h6>Actual Calvings</h6>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .calendar-grid {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
    }
    
    .calendar-day {
        border: 1px solid #e9ecef;
        min-height: 120px;
        padding: 5px;
        background-color: #fff;
        border-radius: 4px;
    }
    
    .calendar-day.today {
        background-color: rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }
    
    .calendar-day-header {
        text-align: center;
        font-weight: bold;
        padding-bottom: 5px;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 5px;
    }
    
    .calendar-day-content {
        max-height: 100px;
        overflow-y: auto;
    }
    
    .calendar-event {
        font-size: 0.75rem;
    }
    
    .calendar-event .badge {
        padding: 2px 4px;
        font-size: 0.7rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Change month function
        window.changeMonth = function(delta) {
            const monthInput = document.getElementById('month');
            const currentDate = new Date(monthInput.value + '-01');
            currentDate.setMonth(currentDate.getMonth() + delta);
            
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            
            monthInput.value = `${year}-${month}`;
            monthInput.form.submit();
        };
        
        // Auto-update calendar on month change
        const monthInput = document.getElementById('month');
        if (monthInput) {
            monthInput.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>
@endpush
@endsection