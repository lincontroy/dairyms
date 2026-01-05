<div class="summary">
    <h3>{{ ucwords(str_replace('_', ' ', $reportType)) }} Report</h3>
    <p><strong>Report generated for period:</strong> 
        {{ $startDate ? $startDate->format('M d, Y') : 'Beginning' }} 
        to {{ $endDate->format('M d, Y') }}
    </p>
</div>

@if(isset($data['summary']) && is_array($data['summary']))
<h3>Summary</h3>
<table>
    <tbody>
        @foreach($data['summary'] as $key => $value)
            @if(is_string($value) || is_numeric($value))
            <tr>
                <td><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong></td>
                <td>{{ $value }}</td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>
@endif

@if(isset($data['records']) && $data['records']->count() > 0)
<h3>Detailed Records</h3>
<p>Total records: {{ $data['records']->count() }}</p>
@endif

<p><em>For detailed view, please use the web interface.</em></p>