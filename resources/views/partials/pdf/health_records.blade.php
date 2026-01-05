<div class="summary">
    <h3>Health Records Summary</h3>
    <table>
        <tr>
            <td><strong>Total Health Records:</strong></td>
            <td>{{ $data['summary']['total_records'] }}</td>
        </tr>
        <tr>
            <td><strong>Animals Under Treatment:</strong></td>
            <td>{{ $data['summary']['under_treatment'] }}</td>
        </tr>
        <tr>
            <td><strong>Recovered Cases:</strong></td>
            <td>{{ $data['summary']['recovered'] }}</td>
        </tr>
        <tr>
            <td><strong>Unique Diagnoses:</strong></td>
            <td>{{ count($data['summary']['by_diagnosis']) }}</td>
        </tr>
    </table>
</div>

@if($data['records']->count() > 0)
<h3>Health Records Details</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Animal ID</th>
            <th>Diagnosis</th>
            <th>Clinical Signs</th>
            <th>Treatment</th>
            <th>Veterinarian</th>
            <th>Outcome</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['records']->sortByDesc('date') as $record)
        <tr>
            <td>{{ $record->date->format('M d, Y') }}</td>
            <td>{{ $record->animal->animal_id }}</td>
            <td>{{ $record->diagnosis }}</td>
            <td>{{ $record->clinical_signs ?? 'N/A' }}</td>
            <td>{{ $record->treatment ?? 'N/A' }}</td>
            <td>{{ $record->veterinarian ?? 'N/A' }}</td>
            <td>{{ $record->outcome }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if(count($data['summary']['by_diagnosis']) > 0)
<h3>Top 10 Diagnoses</h3>
<table>
    <thead>
        <tr>
            <th>Diagnosis</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['summary']['by_diagnosis']->sortDesc()->take(10) as $diagnosis => $count)
        <tr>
            <td>{{ $diagnosis }}</td>
            <td>{{ $count }}</td>
            <td>
                @if($data['summary']['total_records'] > 0)
                    {{ number_format(($count / $data['summary']['total_records']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if($data['animal_health']->count() > 0)
<h3>Animal Health History</h3>
<table>
    <thead>
        <tr>
            <th>Animal ID</th>
            <th>Total Treatments</th>
            <th>Latest Issue</th>
            <th>Current Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['animal_health'] as $animalId => $healthData)
        <tr>
            <td>{{ $animalId }}</td>
            <td>{{ $healthData['total_treatments'] }}</td>
            <td>{{ $healthData['latest_issue'] }}</td>
            <td>
                @if($healthData['under_treatment'])
                    <strong style="color: #dc3545;">Under Treatment</strong>
                @else
                    <strong style="color: #28a745;">Healthy</strong>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@else
<p><strong>No health records found for the selected period.</strong></p>
@endif