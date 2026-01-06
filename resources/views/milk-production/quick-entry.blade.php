@extends('layouts.app')

@section('title', 'Quick Milk Entry - Dairy Farm Management')
@section('page-title', 'Quick Milk Entry')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('milk-production.index') }}">Milk Production</a>
    </li>
    <li class="breadcrumb-item active">Quick Entry</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Quick Milk Entry for {{ today()->format('F j, Y') }}
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-clock me-1"></i>Sessions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="showAllSessions()">
                                <i class="fas fa-list me-2"></i>All Sessions
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="showSession('morning')">
                                <i class="fas fa-sun text-warning me-2"></i>Morning Only
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showSession('afternoon')">
                                <i class="fas fa-sun text-orange me-2"></i>Afternoon Only
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showSession('evening')">
                                <i class="fas fa-moon text-info me-2"></i>Evening Only
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('milk-production.store-multiple') }}">
                        @csrf
                        
                        <!-- Date Selection -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="date" class="form-label">
                                    <i class="fas fa-calendar-alt me-2"></i>Date
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date" 
                                       name="date" 
                                       value="{{ today()->format('Y-m-d') }}" 
                                       required>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Enter milk yields for lactating animals (Morning, Afternoon, Evening)
                                </div>
                            </div>
                        </div>
                        
                        <!-- Session Legend -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex gap-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning me-2">AM</span>
                                        <small>Morning (6AM-10AM)</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-orange me-2">PM</span>
                                        <small>Afternoon (12PM-3PM)</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info me-2">EVE</span>
                                        <small>Evening (5PM-8PM)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Entry Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Animal ID</th>
                                        <th>Name</th>
                                        <th>Breed</th>
                                        <th>Lactation</th>
                                        <th class="morning-session">
                                            <i class="fas fa-sun text-warning me-1"></i>Morning (L)
                                        </th>
                                        <th class="afternoon-session">
                                            <i class="fas fa-sun text-orange me-1"></i>Afternoon (L)
                                        </th>
                                        <th class="evening-session">
                                            <i class="fas fa-moon text-info me-1"></i>Evening (L)
                                        </th>
                                        <th>Total (L)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($animals as $index => $animal)
                                    <tr class="animal-row" data-id="{{ $animal->id }}">
                                        <td>
                                            <strong>{{ $animal->animal_id }}</strong>
                                            <input type="hidden" 
                                                   name="records[{{ $animal->id }}][animal_id]" 
                                                   value="{{ $animal->id }}">
                                        </td>
                                        <td>{{ $animal->name ?? 'Unnamed' }}</td>
                                        <td>{{ $animal->breed }}</td>
                                        <td>{{ $animal->current_lactation ?? 'N/A' }}</td>
                                        
                                        <!-- Morning Yield -->
                                        <td style="width: 140px;">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control morning-yield session-input" 
                                                       name="records[{{ $animal->id }}][morning_yield]" 
                                                       data-animal-id="{{ $animal->id }}"
                                                       data-session="morning"
                                                       step="0.1" 
                                                       min="0" 
                                                       max="100"
                                                       placeholder="0.0"
                                                       oninput="calculateRowTotal({{ $animal->id }})">
                                                <span class="input-group-text">L</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Afternoon Yield -->
                                        <td style="width: 140px;">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control afternoon-yield session-input" 
                                                       name="records[{{ $animal->id }}][afternoon_yield]" 
                                                       data-animal-id="{{ $animal->id }}"
                                                       data-session="afternoon"
                                                       step="0.1" 
                                                       min="0" 
                                                       max="100"
                                                       placeholder="0.0"
                                                       oninput="calculateRowTotal({{ $animal->id }})">
                                                <span class="input-group-text">L</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Evening Yield -->
                                        <td style="width: 140px;">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control evening-yield session-input" 
                                                       name="records[{{ $animal->id }}][evening_yield]" 
                                                       data-animal-id="{{ $animal->id }}"
                                                       data-session="evening"
                                                       step="0.1" 
                                                       min="0" 
                                                       max="100"
                                                       placeholder="0.0"
                                                       oninput="calculateRowTotal({{ $animal->id }})">
                                                <span class="input-group-text">L</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Total -->
                                        <td>
                                            <span class="total-yield fw-bold" id="total-{{ $animal->id }}">0.0</span> L
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Daily Totals:</strong></td>
                                        <td id="totalMorning">0.0</td>
                                        <td id="totalAfternoon">0.0</td>
                                        <td id="totalEvening">0.0</td>
                                        <td id="grandTotal">0.0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <button type="button" class="btn btn-outline-danger" onclick="clearAllYields()">
                                                <i class="fas fa-trash-alt me-2"></i>Clear All
                                            </button>
                                            <button type="button" class="btn btn-outline-info" onclick="copySession('morning', 'afternoon')">
                                                <i class="fas fa-copy me-2"></i>Copy AM → PM
                                            </button>
                                            <button type="button" class="btn btn-outline-warning" onclick="copySession('afternoon', 'evening')">
                                                <i class="fas fa-copy me-2"></i>Copy PM → EVE
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" onclick="quickFillAll()">
                                                <i class="fas fa-bolt me-2"></i>Quick Fill
                                            </button>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-success dropdown-toggle" type="button" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-prescription-bottle me-2"></i>Presets
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="applyPreset('standard')">
                                                        Standard (15-5-10)
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="applyPreset('high')">
                                                        High Yield (20-8-15)
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="applyPreset('low')">
                                                        Low Yield (8-3-5)
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <!-- Session Quick Fill -->
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">Morning</span>
                                                    <input type="number" id="quickMorning" class="form-control" placeholder="Value" step="0.1">
                                                    <button class="btn btn-outline-warning" type="button" onclick="quickFillSession('morning')">
                                                        Fill
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">Afternoon</span>
                                                    <input type="number" id="quickAfternoon" class="form-control" placeholder="Value" step="0.1">
                                                    <button class="btn btn-outline-orange" type="button" onclick="quickFillSession('afternoon')">
                                                        Fill
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">Evening</span>
                                                    <input type="number" id="quickEvening" class="form-control" placeholder="Value" step="0.1">
                                                    <button class="btn btn-outline-info" type="button" onclick="quickFillSession('evening')">
                                                        Fill
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>Session Notes
                            </label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2"
                                      placeholder="Any observations about today's milking sessions..."></textarea>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('milk-production.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="saveAsDraft()">
                                    <i class="fas fa-save me-2"></i>Save Draft
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Records
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .morning-session {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .afternoon-session {
        background-color: rgba(253, 126, 20, 0.1) !important;
    }
    
    .evening-session {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }
    
    .session-input:focus {
        box-shadow: none;
        border-color: #6c757d;
    }
    
    .morning-yield:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .afternoon-yield:focus {
        border-color: #fd7e14;
        box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
    }
    
    .evening-yield:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
    }
    
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white;
    }
    
    .btn-outline-orange {
        color: #fd7e14;
        border-color: #fd7e14;
    }
    
    .btn-outline-orange:hover {
        background-color: #fd7e14;
        color: white;
    }
    
    .animal-row:hover {
        background-color: #f8f9fa;
    }
    
    .total-yield {
        font-size: 1.1em;
        color: #2E7D32;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize totals calculation
    document.addEventListener('DOMContentLoaded', function() {
        calculateAllTotals();
    });
    
    // Calculate total for a single animal
    function calculateRowTotal(animalId) {
        const morningInput = document.querySelector(`input[name="records[${animalId}][morning_yield]"]`);
        const afternoonInput = document.querySelector(`input[name="records[${animalId}][afternoon_yield]"]`);
        const eveningInput = document.querySelector(`input[name="records[${animalId}][evening_yield]"]`);
        const totalSpan = document.getElementById(`total-${animalId}`);
        
        const morning = parseFloat(morningInput.value) || 0;
        const afternoon = parseFloat(afternoonInput.value) || 0;
        const evening = parseFloat(eveningInput.value) || 0;
        const total = morning + afternoon + evening;
        
        totalSpan.textContent = total.toFixed(1);
        calculateGrandTotals();
    }
    
    // Calculate grand totals
    function calculateGrandTotals() {
        let totalMorning = 0;
        let totalAfternoon = 0;
        let totalEvening = 0;
        let grandTotal = 0;
        
        document.querySelectorAll('.animal-row').forEach(row => {
            const animalId = row.getAttribute('data-id');
            const morningInput = document.querySelector(`input[name="records[${animalId}][morning_yield]"]`);
            const afternoonInput = document.querySelector(`input[name="records[${animalId}][afternoon_yield]"]`);
            const eveningInput = document.querySelector(`input[name="records[${animalId}][evening_yield]"]`);
            
            totalMorning += parseFloat(morningInput.value) || 0;
            totalAfternoon += parseFloat(afternoonInput.value) || 0;
            totalEvening += parseFloat(eveningInput.value) || 0;
        });
        
        grandTotal = totalMorning + totalAfternoon + totalEvening;
        
        document.getElementById('totalMorning').textContent = totalMorning.toFixed(1);
        document.getElementById('totalAfternoon').textContent = totalAfternoon.toFixed(1);
        document.getElementById('totalEvening').textContent = totalEvening.toFixed(1);
        document.getElementById('grandTotal').textContent = grandTotal.toFixed(1);
    }
    
    // Calculate all totals initially
    function calculateAllTotals() {
        document.querySelectorAll('.animal-row').forEach(row => {
            const animalId = row.getAttribute('data-id');
            calculateRowTotal(animalId);
        });
    }
    
    // Clear all yields
    function clearAllYields() {
        if (!confirm('Clear all milk yields?')) return;
        
        document.querySelectorAll('.session-input').forEach(input => {
            input.value = '';
        });
        calculateAllTotals();
    }
    
    // Copy from one session to another
    function copySession(fromSession, toSession) {
        document.querySelectorAll(`.${fromSession}-yield`).forEach((fromInput, index) => {
            const toInput = document.querySelectorAll(`.${toSession}-yield`)[index];
            if (fromInput.value && toInput) {
                toInput.value = fromInput.value;
            }
        });
        calculateAllTotals();
    }
    
    // Quick fill all animals with same values
    function quickFillAll() {
        const morningValue = prompt('Enter morning yield for all animals:', '15');
        const afternoonValue = prompt('Enter afternoon yield for all animals:', '5');
        const eveningValue = prompt('Enter evening yield for all animals:', '10');
        
        if (morningValue !== null && afternoonValue !== null && eveningValue !== null) {
            document.querySelectorAll('.animal-row').forEach(row => {
                const animalId = row.getAttribute('data-id');
                const morningInput = document.querySelector(`input[name="records[${animalId}][morning_yield]"]`);
                const afternoonInput = document.querySelector(`input[name="records[${animalId}][afternoon_yield]"]`);
                const eveningInput = document.querySelector(`input[name="records[${animalId}][evening_yield]"]`);
                
                morningInput.value = parseFloat(morningValue) || 0;
                afternoonInput.value = parseFloat(afternoonValue) || 0;
                eveningInput.value = parseFloat(eveningValue) || 0;
            });
            calculateAllTotals();
        }
    }
    
    // Apply preset values
    function applyPreset(presetType) {
        let morning, afternoon, evening;
        
        switch(presetType) {
            case 'standard':
                morning = 15; afternoon = 5; evening = 10;
                break;
            case 'high':
                morning = 20; afternoon = 8; evening = 15;
                break;
            case 'low':
                morning = 8; afternoon = 3; evening = 5;
                break;
            default:
                morning = 15; afternoon = 5; evening = 10;
        }
        
        document.querySelectorAll('.animal-row').forEach(row => {
            const animalId = row.getAttribute('data-id');
            const morningInput = document.querySelector(`input[name="records[${animalId}][morning_yield]"]`);
            const afternoonInput = document.querySelector(`input[name="records[${animalId}][afternoon_yield]"]`);
            const eveningInput = document.querySelector(`input[name="records[${animalId}][evening_yield]"]`);
            
            morningInput.value = morning;
            afternoonInput.value = afternoon;
            eveningInput.value = evening;
        });
        calculateAllTotals();
    }
    
    // Quick fill specific session
    function quickFillSession(session) {
        const inputId = `quick${session.charAt(0).toUpperCase() + session.slice(1)}`;
        const value = document.getElementById(inputId).value;
        
        if (!value) {
            alert('Please enter a value first');
            return;
        }
        
        const numericValue = parseFloat(value);
        if (isNaN(numericValue) || numericValue < 0) {
            alert('Please enter a valid number');
            return;
        }
        
        document.querySelectorAll(`.${session}-yield`).forEach(input => {
            input.value = numericValue;
        });
        
        calculateAllTotals();
    }
    
    // Show/hide sessions
    function showSession(session) {
        // Highlight active session
        document.querySelectorAll('th, td').forEach(cell => {
            cell.classList.remove('active-session');
        });
        
        // Show only selected session columns
        document.querySelectorAll(`.${session}-session`).forEach(cell => {
            cell.classList.add('active-session');
        });
        
        // Show message
        alert(`Showing ${session} session only. Other sessions are hidden but data is preserved.`);
    }
    
    function showAllSessions() {
        document.querySelectorAll('th, td').forEach(cell => {
            cell.classList.remove('active-session');
        });
    }
    
    // Save as draft (local storage)
    function saveAsDraft() {
        const formData = {
            date: document.getElementById('date').value,
            notes: document.getElementById('notes').value,
            records: {}
        };
        
        document.querySelectorAll('.animal-row').forEach(row => {
            const animalId = row.getAttribute('data-id');
            const morningInput = document.querySelector(`input[name="records[${animalId}][morning_yield]"]`);
            const afternoonInput = document.querySelector(`input[name="records[${animalId}][afternoon_yield]"]`);
            const eveningInput = document.querySelector(`input[name="records[${animalId}][evening_yield]"]`);
            
            formData.records[animalId] = {
                morning_yield: morningInput.value,
                afternoon_yield: afternoonInput.value,
                evening_yield: eveningInput.value
            };
        });
        
        localStorage.setItem('milkDraft', JSON.stringify(formData));
        alert('Draft saved locally. You can continue later.');
    }
    
    // Load draft from local storage
    function loadDraft() {
        const draft = localStorage.getItem('milkDraft');
        if (draft) {
            if (confirm('Load saved draft?')) {
                const formData = JSON.parse(draft);
                
                document.getElementById('date').value = formData.date;
                document.getElementById('notes').value = formData.notes;
                
                Object.keys(formData.records).forEach(animalId => {
                    const record = formData.records[animalId];
                    const morningInput = document.querySelector(`input[name="records[${animalId}][morning_yield]"]`);
                    const afternoonInput = document.querySelector(`input[name="records[${animalId}][afternoon_yield]"]`);
                    const eveningInput = document.querySelector(`input[name="records[${animalId}][evening_yield]"]`);
                    
                    if (morningInput) morningInput.value = record.morning_yield;
                    if (afternoonInput) afternoonInput.value = record.afternoon_yield;
                    if (eveningInput) eveningInput.value = record.evening_yield;
                });
                
                calculateAllTotals();
            }
        }
    }
    
    // Load draft on page load
    window.addEventListener('load', function() {
        setTimeout(loadDraft, 1000);
    });
</script>
@endpush