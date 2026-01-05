@extends('layouts.app')

@section('title', 'Report View - Dairy Farm Management')
@section('page-title', 'Report View: ' . ucwords(str_replace('_', ' ', $reportType)))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('reports.index') }}">Reports</a>
    </li>
    <li class="breadcrumb-item active">View</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Report Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title mb-2">
                                <i class="fas fa-chart-bar me-2 text-primary"></i>
                                {{ ucwords(str_replace('_', ' ', $reportType)) }} Report
                            </h4>
                            <p class="card-text mb-0">
                                <span class="text-muted">
                                    Period: 
                                    {{ $startDate ? $startDate->format('M d, Y') : 'Beginning' }} 
                                    to 
                                    {{ $endDate->format('M d, Y') }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Back
                                </a>
                                <button type="button" class="btn btn-success" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i>Print
                                </button>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-download me-1"></i>Download
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" 
                                               onclick="downloadReport('pdf')">
                                                <i class="fas fa-file-pdf me-2 text-danger"></i>PDF
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" 
                                               onclick="downloadReport('excel')">
                                                <i class="fas fa-file-excel me-2 text-success"></i>Excel
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Report Content -->
    <div class="row">
        <div class="col-12">
            <!-- Dynamic Report Content -->
            @include('reports.partials.' . $reportType, ['data' => $data])
        </div>
    </div>
</div>

@push('scripts')
<script>
    function downloadReport(format) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('reports.generate') }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const reportType = document.createElement('input');
        reportType.type = 'hidden';
        reportType.name = 'report_type';
        reportType.value = '{{ $reportType }}';
        form.appendChild(reportType);
        
        const startDate = document.createElement('input');
        startDate.type = 'hidden';
        startDate.name = 'start_date';
        startDate.value = '{{ $startDate ? $startDate->format('Y-m-d') : '' }}';
        form.appendChild(startDate);
        
        const endDate = document.createElement('input');
        endDate.type = 'hidden';
        endDate.name = 'end_date';
        endDate.value = '{{ $endDate->format('Y-m-d') }}';
        form.appendChild(endDate);
        
        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = format;
        form.appendChild(formatInput);
        
        // Add filters
        @foreach(request()->except(['_token', 'report_type', 'start_date', 'end_date', 'format']) as $key => $value)
            const filter{{ $key }} = document.createElement('input');
            filter{{ $key }}.type = 'hidden';
            filter{{ $key }}.name = '{{ $key }}';
            filter{{ $key }}.value = '{{ $value }}';
            form.appendChild(filter{{ $key }});
        @endforeach
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>
@endpush
@endsection