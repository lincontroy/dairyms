<div class="summary">
    <h3>Production Summary</h3>
    <table>
        <tr>
            <td><strong>Total Milk Production:</strong></td>
            <td>{{ number_format($data['summary']['total_milk'], 1) }} liters</td>
        </tr>
        <tr>
            <td><strong>Total Records:</strong></td>
            <td>{{ $data['summary']['total_records'] }}</td>
        </tr>
        <tr>
            <td><strong>Average Daily Production:</strong></td>
            <td>{{ number_format($data['summary']['average_daily'], 1) }} liters</td>
        </tr>
        <tr>
            <td><strong>Approved Records:</strong></td>
            <td>{{ $data['summary']['approved_records'] }}</td>
        </tr>
        <tr>
            <td><strong>Pending Records:</strong></td>
            <td>{{ $data['summary']['pending_records'] }}</td>
        </tr>
    </table>
</div>

@if($data['records']->count() > 0)
<h3>Milk Production Details</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Animal ID</th>
            <th>Morning (L)</th>
            <th>Evening (L)</th>
            <th>Total (L)</th>
            <th>Lactation</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['records']->sortByDesc('date') as $record)
        <tr>
            <td>{{ $record->date->format('M d, Y') }}</td>
            <td>{{ $record->animal->animal_id }}</td>
            <td>{{ number_format($record->morning_yield, 1) }}</td>
            <td>{{ number_format($record->evening_yield, 1) }}</td>
            <td>{{ number_format($record->total_yield, 1) }}</td>
            <td>{{ $record->lactation_number ?? 'N/A' }}</td>
            <td>{{ ucfirst($record->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if($data['daily_totals']->count() > 0)
<h3>Daily Summary</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Morning Total (L)</th>
            <th>Evening Total (L)</th>
            <th>Daily Total (L)</th>
            <th># of Records</th>
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
@endif

@if($data['animal_totals']->count() > 0)
<h3>Animal-wise Summary</h3>
<table>
    <thead>
        <tr>
            <th>Animal ID</th>
            <th>Total Milk (L)</th>
            <th>Average Daily (L)</th>
            <th># of Records</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['animal_totals'] as $animalId => $animalData)
        <tr>
            <td>{{ $animalData['animal']->animal_id }}</td>
            <td>{{ number_format($animalData['total_milk'], 1) }}</td>
            <td>{{ number_format($animalData['average_daily'], 1) }}</td>
            <td>{{ $animalData['records_count'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@else
<p><strong>No milk production records found for the selected period.</strong></p>
@endif