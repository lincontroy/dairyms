@extends('layouts.app')

@section('title', 'Reports - Dairy Farm Management')
@section('page-title', 'Generate Reports')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Generate Reports
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.generate') }}" method="POST" id="reportForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="report_type" class="form-label">
                                    <i class="fas fa-file-alt me-1"></i>Report Type
                                </label>
                                <select name="report_type" id="report_type" class="form-select" required>
                                    <option value="">Select Report Type</option>
                                    @foreach($reportTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="format" class="form-label">
                                    <i class="fas fa-download me-1"></i>Output Format
                                </label>
                                <select name="format" id="format" class="form-select">
                                    <option value="html">View in Browser</option>
                                    <option value="pdf">Download PDF</option>
                                    <option value="excel">Download Excel</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>Start Date
                                </label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="form-control" 
                                       max="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>End Date
                                </label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="form-control"
                                       max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        
                        <!-- Dynamic Filters -->
                        <div id="dynamicFilters">
                            <!-- Filters will be loaded here based on report type -->
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="fas fa-redo me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-play me-1"></i>Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Reports -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="#" class="card quick-report-card text-center text-decoration-none h-100"
                               onclick="setQuickReport('milk_production', 'today')">
                                <div class="card-body">
                                    <div class="quick-report-icon mb-3">
                                        <i class="fas fa-wine-bottle fa-2x text-info"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Today's Milk</h6>
                                    <small class="text-muted">Daily production report</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="#" class="card quick-report-card text-center text-decoration-none h-100"
                               onclick="setQuickReport('health_records', 'current_month')">
                                <div class="card-body">
                                    <div class="quick-report-icon mb-3">
                                        <i class="fas fa-heartbeat fa-2x text-danger"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Health Issues</h6>
                                    <small class="text-muted">This month's health records</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="#" class="card quick-report-card text-center text-decoration-none h-100"
                               onclick="setQuickReport('breeding_records', 'current_pregnancies')">
                                <div class="card-body">
                                    <div class="quick-report-icon mb-3">
                                        <i class="fas fa-baby fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="card-title mb-0">Pregnant Animals</h6>
                                    <small class="text-muted">Current pregnancy status</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .quick-report-card {
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .quick-report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        border-color: var(--farm-green);
    }
    
    .quick-report-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(46, 125, 50, 0.1);
    }
    
    .filter-section {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .filter-section h6 {
        color: #495057;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 2)
            .toISOString().split('T')[0];
        
        document.getElementById('end_date').value = today;
        document.getElementById('start_date').value = firstDayOfMonth;
        
        // Load filters when report type changes
        document.getElementById('report_type').addEventListener('change', function() {
            loadReportFilters(this.value);
        });
        
        // Initialize filters for default selection
        const initialType = document.getElementById('report_type').value;
        if (initialType) {
            loadReportFilters(initialType);
        }
    });
    
    function loadReportFilters(reportType) {
        const filtersDiv = document.getElementById('dynamicFilters');
        filtersDiv.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Loading filters...</div>';
        
        // In a real application, you would fetch this via AJAX
        // For simplicity, we'll use client-side logic
        setTimeout(() => {
            let filtersHtml = '';
            
            switch(reportType) {
                case 'animals':
                    filtersHtml = `
                        <div class="filter-section">
                            <h6><i class="fas fa-filter me-2"></i>Animal Filters</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Breed</label>
                                    <select name="breed" class="form-select">
                                        <option value="">All Breeds</option>
                                        @foreach(['Holstein', 'Jersey', 'Guernsey', 'Ayrshire', 'Brown Swiss', 'Other'] as $breed)
                                            <option value="{{ $breed }}">{{ $breed }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        @foreach(['calf', 'heifer', 'lactating', 'dry', 'pregnant', 'sold', 'dead'] as $status)
                                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sex</label>
                                    <select name="sex" class="form-select">
                                        <option value="">All</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'milk_production':
                    filtersHtml = `
                        <div class="filter-section">
                            <h6><i class="fas fa-filter me-2"></i>Milk Production Filters</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Animal ID</label>
                                    <input type="text" name="animal_id" class="form-control" 
                                           placeholder="Enter animal ID">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Record Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="approved">Approved Only</option>
                                        <option value="pending">Pending Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'health_records':
                    filtersHtml = `
                        <div class="filter-section">
                            <h6><i class="fas fa-filter me-2"></i>Health Records Filters</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Diagnosis</label>
                                    <input type="text" name="diagnosis" class="form-control" 
                                           placeholder="Search diagnosis">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Outcome</label>
                                    <select name="outcome" class="form-select">
                                        <option value="">All Outcomes</option>
                                        <option value="Recovered">Recovered</option>
                                        <option value="Under Treatment">Under Treatment</option>
                                        <option value="Chronic">Chronic</option>
                                        <option value="Died">Died</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'breeding_records':
                    filtersHtml = `
                        <div class="filter-section">
                            <h6><i class="fas fa-filter me-2"></i>Breeding Records Filters</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pregnancy Result</label>
                                    <select name="pregnancy_result" class="form-select">
                                        <option value="">All</option>
                                        <option value="true">Successful Only</option>
                                        <option value="false">Unsuccessful Only</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breeding Method</label>
                                    <select name="breeding_method" class="form-select">
                                        <option value="">All Methods</option>
                                        @foreach(['Natural', 'Artificial Insemination', 'Embryo Transfer'] as $method)
                                            <option value="{{ $method }}">{{ $method }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                    
                default:
                    filtersHtml = '<div class="alert alert-info">No additional filters for this report type.</div>';
            }
            
            filtersDiv.innerHTML = filtersHtml;
        }, 300);
    }
    
    function setQuickReport(type, preset) {
        const today = new Date().toISOString().split('T')[0];
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const reportType = document.getElementById('report_type');
        
        reportType.value = type;
        
        switch(preset) {
            case 'today':
                startDate.value = today;
                endDate.value = today;
                break;
            case 'current_month':
                const firstDay = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
                    .toISOString().split('T')[0];
                startDate.value = firstDay;
                endDate.value = today;
                break;
            case 'current_pregnancies':
                // Last 6 months for breeding records
                const sixMonthsAgo = new Date();
                sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6);
                startDate.value = sixMonthsAgo.toISOString().split('T')[0];
                endDate.value = today;
                break;
        }
        
        // Trigger filter loading
        loadReportFilters(type);
        
        // Scroll to form
        document.getElementById('reportForm').scrollIntoView({ behavior: 'smooth' });
    }
    
    function resetForm() {
        document.getElementById('reportForm').reset();
        const today = new Date().toISOString().split('T')[0];
        const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 2)
            .toISOString().split('T')[0];
        
        document.getElementById('end_date').value = today;
        document.getElementById('start_date').value = firstDayOfMonth;
        document.getElementById('dynamicFilters').innerHTML = '';
    }
</script>
@endpush
@endsection