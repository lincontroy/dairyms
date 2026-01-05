<div class="summary">
    <h3>Animal Registry Summary</h3>
    <table>
        <tr>
            <td><strong>Total Animals:</strong></td>
            <td>{{ $data['summary']['total'] }}</td>
        </tr>
        <tr>
            <td><strong>Active Animals:</strong></td>
            <td>{{ $data['summary']['active'] }}</td>
        </tr>
        <tr>
            <td><strong>Number of Breeds:</strong></td>
            <td>{{ count($data['summary']['by_breed']) }}</td>
        </tr>
        <tr>
            <td><strong>Status Types:</strong></td>
            <td>{{ count($data['summary']['by_status']) }}</td>
        </tr>
    </table>
</div>

@if($data['animals']->count() > 0)
<h3>Animal Details</h3>
<table>
    <thead>
        <tr>
            <th>Animal ID</th>
            <th>Name</th>
            <th>Breed</th>
            <th>Status</th>
            <th>Date of Birth</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Date Added</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['animals'] as $animal)
        <tr>
            <td>{{ $animal->animal_id }}</td>
            <td>{{ $animal->name ?? 'N/A' }}</td>
            <td>{{ $animal->breed }}</td>
            <td>{{ ucfirst($animal->status) }}</td>
            <td>{{ $animal->date_of_birth ? $animal->date_of_birth->format('M d, Y') : 'N/A' }}</td>
            <td>
                @if($animal->date_of_birth)
                    {{ $animal->date_of_birth->diffInYears() }} years
                @else
                    N/A
                @endif
            </td>
            <td>{{ ucfirst($animal->sex) }}</td>
            <td>{{ $animal->date_added->format('M d, Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@if(count($data['summary']['by_breed']) > 0)
<h3>Breed Distribution</h3>
<table>
    <thead>
        <tr>
            <th>Breed</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['summary']['by_breed'] as $breed => $count)
        <tr>
            <td>{{ $breed }}</td>
            <td>{{ $count }}</td>
            <td>
                @if($data['summary']['total'] > 0)
                    {{ number_format(($count / $data['summary']['total']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(count($data['summary']['by_status']) > 0)
<h3>Status Distribution</h3>
<table>
    <thead>
        <tr>
            <th>Status</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['summary']['by_status'] as $status => $count)
        <tr>
            <td>{{ ucfirst($status) }}</td>
            <td>{{ $count }}</td>
            <td>
                @if($data['summary']['total'] > 0)
                    {{ number_format(($count / $data['summary']['total']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@else
<p><strong>No animals found for the selected criteria.</strong></p>
@endif