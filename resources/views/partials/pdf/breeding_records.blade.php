<div class="summary">
    <h3>Breeding Records Summary</h3>
    <table>
        <tr>
            <td><strong>Total Breeding Services:</strong></td>
            <td>{{ $data['summary']['total_services'] }}</td>
        </tr>
        <tr>
            <td><strong>Successful Pregnancies:</strong></td>
            <td>{{ $data['summary']['successful_pregnancies'] }}</td>
        </tr>
        <tr>
            <td><strong>Success Rate:</strong></td>
            <td>{{ number_format($data['summary']['success_rate'], 1) }}%</td>
        </tr>
        <tr>
            <td><strong>Current Pregnancies:</strong></td>
            <td>{{ $data['summary']['current_pregnancies'] }}</td>
        </tr>
        <tr>
            <td><strong>Calvings This Period:</strong></td>
            <td>{{ $data['summary']['calvings_this_period'] }}</td>
        </tr>
    </table>
</div>

@if($data['current_pregnancies']->count() > 0)
<h3>Current Pregnancies</h3>
<table>
    <thead>
        <tr>
            <th>Animal ID</th>
            <th>Service Date</th>
            <th>Days Pregnant</th>
            <th>Expected Calving</th>
            <th>Method</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['current_pregnancies'] as $record)
        <tr>
            <td>{{ $record->animal->animal_id }}</td>
            <td>{{ $record->date_of_service->format('M d, Y') }}</td>
            <td>
                {{ $record->days_pregnant ?? 'N/A' }}
                @if($record->days_pregnant >= 270)
                    (Due Soon)
                @endif
            </td>
            <td>
                @if($record->expected_calving_date)
                    {{ $record->expected_calving_date->format('M d, Y') }}
                @else
                    N/A
                @endif
            </td>
            <td>{{ $record->breeding_method }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if($data['records']->count() > 0)
<h3>All Breeding Records</h3>
<table>
    <thead>
        <tr>
            <th>Service Date</th>
            <th>Animal ID</th>
            <th>Breeding Method</th>
            <th>Pregnancy Result</th>
            <th>Expected Calving</th>
            <th>Actual Calving</th>
            <th>Calving Outcome</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['records']->sortByDesc('date_of_service') as $record)
        <tr>
            <td>{{ $record->date_of_service->format('M d, Y') }}</td>
            <td>{{ $record->animal->animal_id }}</td>
            <td>{{ $record->breeding_method }}</td>
            <td>
                @if($record->pregnancy_result)
                    Successful
                @else
                    Unsuccessful
                @endif
            </td>
            <td>
                @if($record->expected_calving_date)
                    {{ $record->expected_calving_date->format('M d, Y') }}
                @else
                    N/A
                @endif
            </td>
            <td>
                @if($record->actual_calving_date)
                    {{ $record->actual_calving_date->format('M d, Y') }}
                @else
                    @if($record->pregnancy_result)
                        Pending
                    @else
                        N/A
                    @endif
                @endif
            </td>
            <td>{{ $record->calving_outcome ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if(count($data['method_stats']) > 0)
<h3>Breeding Method Statistics</h3>
<table>
    <thead>
        <tr>
            <th>Method</th>
            <th>Count</th>
            <th>Successful</th>
            <th>Success Rate</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['method_stats'] as $method => $stats)
        <tr>
            <td>{{ $method }}</td>
            <td>{{ $stats['count'] }}</td>
            <td>{{ $stats['successful'] }}</td>
            <td>{{ number_format($stats['success_rate'], 1) }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@else
<p><strong>No breeding records found for the selected period.</strong></p>
@endif