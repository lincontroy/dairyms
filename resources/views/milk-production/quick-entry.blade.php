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
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Quick Milk Entry for Today
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('milk-production.store-multiple') }}">
                        @csrf
                        
                        <!-- Date Selection -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date" 
                                       name="date" 
                                       value="{{ today()->format('Y-m-d') }}" 
                                       required>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="form-text">
                                    Enter milk yields for all lactating animals
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
                                        <th>Morning (L)</th>
                                        <th>Evening (L)</th>
                                        <th>Total (L)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($animals as $index => $animal)
                                    <tr>
                                        <td>
                                            <strong>{{ $animal->animal_id }}</strong>
                                            <input type="hidden" 
                                                   name="records[{{ $index }}][animal_id]" 
                                                   value="{{ $animal->id }}">
                                        </td>
                                        <td>{{ $animal->name ?? 'Unnamed' }}</td>
                                        <td>{{ $animal->breed }}</td>
                                        <td>{{ $animal->current_lactation ?? 'N/A' }}</td>
                                        <td style="width: 150px;">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control morning-yield" 
                                                       name="records[{{ $index }}][morning_yield]" 
                                                       data-index="{{ $index }}"
                                                       step="0.1" 
                                                       min="0" 
                                                       max="100"
                                                       placeholder="0.0">
                                                <span class="input-group-text">L</span>
                                            </div>
                                        </td>
                                        <td style="width: 150px;">
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control evening-yield" 
                                                       name="records[{{ $index }}][evening_yield]" 
                                                       data-index="{{ $index }}"
                                                       step="0.1" 
                                                       min="0" 
                                                       max="100"
                                                       placeholder="0.0">
                                                <span class="input-group-text">L</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="total-yield" data-index="{{ $index }}">0.0</span> L
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <td colspan="4" class="text-end"><strong>Daily Total:</strong></td>
                                        <td id="totalMorning">0.0</td>
                                        <td id="totalEvening">0.0</td>
                                        <td id="grandTotal">0.0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary" id="clearAll">
                                        <i class="fas fa-trash-alt me-2"></i>Clear All
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="copyMorningToEvening">
                                        <i class="fas fa-copy me-2"></i>Copy AM to PM
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="calculateTotals">
                                        <i class="fas fa-calculator me-2"></i>Calculate Totals
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">General Notes</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2"
                                      placeholder="Any general observations for today..."></textarea>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('milk-production.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save All Records
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate total for each row
        function calculateRowTotal(index) {
            const morningInput = document.querySelector(`.morning-yield[data-index="${index}"]`);
            const eveningInput = document.querySelector(`.evening-yield[data-index="${index}"]`);
            const totalSpan = document.querySelector(`.total-yield[data-index="${index}"]`);
            
            const morning = parseFloat(morningInput.value) || 0;
            const evening = parseFloat(eveningInput.value) || 0;
            const total = morning + evening;
            
            totalSpan.textContent = total.toFixed(1);
            return { morning, evening, total };
        }
        
        // Calculate grand totals
        function calculateGrandTotals() {
            let totalMorning = 0;
            let totalEvening = 0;
            let grandTotal = 0;
            
            document.querySelectorAll('.morning-yield').forEach((input, index) => {
                const rowTotals = calculateRowTotal(index);
                totalMorning += rowTotals.morning;
                totalEvening += rowTotals.evening;
                grandTotal += rowTotals.total;
            });
            
            document.getElementById('totalMorning').textContent = totalMorning.toFixed(1);
            document.getElementById('totalEvening').textContent = totalEvening.toFixed(1);
            document.getElementById('grandTotal').textContent = grandTotal.toFixed(1);
        }
        
        // Event listeners for yield inputs
        document.querySelectorAll('.morning-yield, .evening-yield').forEach(input => {
            input.addEventListener('input', calculateGrandTotals);
        });
        
        // Clear all inputs
        document.getElementById('clearAll').addEventListener('click', function() {
            document.querySelectorAll('.morning-yield, .evening-yield').forEach(input => {
                input.value = '';
            });
            calculateGrandTotals();
        });
        
        // Copy morning to evening
        document.getElementById('copyMorningToEvening').addEventListener('click', function() {
            document.querySelectorAll('.morning-yield').forEach((morningInput, index) => {
                const eveningInput = document.querySelector(`.evening-yield[data-index="${index}"]`);
                if (morningInput.value) {
                    eveningInput.value = morningInput.value;
                }
            });
            calculateGrandTotals();
        });
        
        // Calculate totals button
        document.getElementById('calculateTotals').addEventListener('click', calculateGrandTotals);
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let hasData = false;
            document.querySelectorAll('.morning-yield, .evening-yield').forEach(input => {
                if (input.value) {
                    hasData = true;
                }
            });
            
            if (!hasData) {
                e.preventDefault();
                alert('Please enter at least one milk yield value.');
                return false;
            }
            
            return true;
        });
        
        // Initialize calculations
        calculateGrandTotals();
    });
</script>
@endpush