<!DOCTYPE html>
<html>
<head>
    <title>{{ ucwords(str_replace('_', ' ', $reportType)) }} Report - Dairy Farm Management</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #2E7D32;
            padding-bottom: 15px;
        }
        .header h1 { 
            color: #2E7D32; 
            margin-bottom: 5px;
            font-size: 24px;
        }
        .header h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .summary { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 5px; 
            margin-bottom: 20px;
            border-left: 4px solid #2E7D32;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
            font-size: 11px;
        }
        th { 
            background: #2E7D32; 
            color: white; 
            padding: 8px 10px; 
            text-align: left; 
            font-weight: bold;
        }
        td { 
            padding: 6px 10px; 
            border-bottom: 1px solid #ddd; 
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        h3 {
            color: #2E7D32;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .footer { 
            margin-top: 50px; 
            padding-top: 15px; 
            border-top: 1px solid #ddd; 
            font-size: 10px; 
            color: #666;
            text-align: center;
        }
        .page-break {
            page-break-before: always;
        }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        .text-info { color: #17a2b8; }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dairy Farm Management System</h1>
        <h2>{{ ucwords(str_replace('_', ' ', $reportType)) }} Report</h2>
        <p>
            <strong>Period:</strong> 
            {{ $startDate ? $startDate->format('F d, Y') : 'Beginning' }} 
            to {{ $endDate->format('F d, Y') }}
        </p>
        <p><strong>Generated:</strong> {{ $generatedAt->format('F d, Y h:i A') }}</p>
    </div>
    
    <!-- Dynamic Content -->
    @include($contentView, ['data' => $data])
    
    <div class="footer">
        <p><strong>Dairy Farm Management System</strong> | Report ID: {{ strtoupper(uniqid()) }}</p>
        <p>Confidential - For Internal Use Only</p>
        <p>Page <span class="page-number"></span> of <span class="page-count"></span></p>
    </div>
    
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->get_font("DejaVu Sans");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>