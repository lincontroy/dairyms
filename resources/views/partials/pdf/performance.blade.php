<div class="summary">
    <h3>Animal Performance Summary</h3>
    <table>
        <tr>
            <td><strong>Total Animals Analyzed:</strong></td>
            <td>{{ $data['summary']['total_animals'] }}</td>
        </tr>
        <tr>
            <td><strong>Average Milk per Animal:</strong></td>
            <td>{{ number_format($data['summary']['avg_milk_per_animal'], 1) }} liters</td>
        </tr>
        <tr>
            <td><strong>Average Health Issues:</strong></td>
            <td>{{ number_format($data['summary']['avg_health_issues'], 1) }}</td>
        </tr>
    </table>
</div>

@if(count($data['summary']['top_performers']) > 0)
<h3>Top 10 Performing Animals</h3>
<table>
    <thead>
        <tr>
            <th>Rank</th>
            <th>Animal ID</th>
            <th>Name</th>
            <th>Total Milk (L)</th>
            <th>Avg Daily (L)</th>
            <th>Health Issues</th>
            <th>Performance Score</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['summary']['top_performers'] as $index => $performance)
        <tr>
            <td>#{{ $index + 1 }}</td>
            <td>{{ $performance['animal']->animal_id }}</td>
            <td>{{ $performance['animal']->name ?? 'N/A' }}</td>
            <td>{{ number_format($performance['total_milk'], 1) }}</td>
            <td>{{ number_format($performance['avg_daily_milk'], 1) }}</td>
            <td>{{ $performance['health_issues'] }}</td>
            <td>
                <strong>{{ number_format($performance['performance_score'], 1) }}</strong>
                @if($performance['performance_score'] >= 80)
                    <span style="color: #28a745;">(Excellent)</span>
                @elseif($performance['performance_score'] >= 60)
                    <span style="color: #ffc107;">(Good)</span>
                @elseif($performance['performance_score'] >= 40)
                    <span style="color: #fd7e14;">(Average)</span>
                @else
                    <span style="color: #dc3545;">(Needs Attention)</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if($data['performance_data']->count() > 0)
<h3>Complete Performance Data</h3>
<table>
    <thead>
        <tr>
            <th>Animal ID</th>
            <th>Breed</th>
            <th>Status</th>
            <th>Total Milk (L)</th>
            <th>Avg Daily (L)</th>
            <th>Milk Days</th>
            <th>Health Issues</th>
            <th>Treatment Cost</th>
            <th>Score</th>
            <th>Grade</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['performance_data'] as $performance)
        @php
            $score = $performance['performance_score'];
            if($score >= 80) $grade = 'A';
            elseif($score >= 70) $grade = 'B';
            elseif($score >= 60) $grade = 'C';
            elseif($score >= 50) $grade = 'D';
            else $grade = 'F';
            
            if($grade == 'A') $color = '#28a745';
            elseif($grade == 'B') $color = '#17a2b8';
            elseif($grade == 'C') $color = '#ffc107';
            elseif($grade == 'D') $color = '#fd7e14';
            else $color = '#dc3545';
        @endphp
        <tr>
            <td>{{ $performance['animal']->animal_id }}</td>
            <td>{{ $performance['animal']->breed }}</td>
            <td>{{ ucfirst($performance['animal']->status) }}</td>
            <td>{{ number_format($performance['total_milk'], 1) }}</td>
            <td>{{ number_format($performance['avg_daily_milk'], 1) }}</td>
            <td>{{ $performance['milk_days'] }}</td>
            <td>{{ $performance['health_issues'] }}</td>
            <td>{{ number_format($performance['treatment_cost'], 2) }}</td>
            <td>{{ number_format($score, 1) }}</td>
            <td style="font-weight: bold; color: {{ $color }};">{{ $grade }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Performance Distribution</h3>
@php
    $gradeDistribution = [
        'A' => 0,
        'B' => 0,
        'C' => 0,
        'D' => 0,
        'F' => 0,
    ];
    
    foreach($data['performance_data'] as $performance) {
        $score = $performance['performance_score'];
        if($score >= 80) $gradeDistribution['A']++;
        elseif($score >= 70) $gradeDistribution['B']++;
        elseif($score >= 60) $gradeDistribution['C']++;
        elseif($score >= 50) $gradeDistribution['D']++;
        else $gradeDistribution['F']++;
    }
@endphp
<table>
    <thead>
        <tr>
            <th>Grade</th>
            <th>Score Range</th>
            <th>Number of Animals</th>
            <th>Percentage</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="color: #28a745; font-weight: bold;">A</td>
            <td>80-100</td>
            <td>{{ $gradeDistribution['A'] }}</td>
            <td>
                @if($data['summary']['total_animals'] > 0)
                    {{ number_format(($gradeDistribution['A'] / $data['summary']['total_animals']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
            <td>Excellent</td>
        </tr>
        <tr>
            <td style="color: #17a2b8; font-weight: bold;">B</td>
            <td>70-79</td>
            <td>{{ $gradeDistribution['B'] }}</td>
            <td>
                @if($data['summary']['total_animals'] > 0)
                    {{ number_format(($gradeDistribution['B'] / $data['summary']['total_animals']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
            <td>Good</td>
        </tr>
        <tr>
            <td style="color: #ffc107; font-weight: bold;">C</td>
            <td>60-69</td>
            <td>{{ $gradeDistribution['C'] }}</td>
            <td>
                @if($data['summary']['total_animals'] > 0)
                    {{ number_format(($gradeDistribution['C'] / $data['summary']['total_animals']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
            <td>Average</td>
        </tr>
        <tr>
            <td style="color: #fd7e14; font-weight: bold;">D</td>
            <td>50-59</td>
            <td>{{ $gradeDistribution['D'] }}</td>
            <td>
                @if($data['summary']['total_animals'] > 0)
                    {{ number_format(($gradeDistribution['D'] / $data['summary']['total_animals']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
            <td>Below Average</td>
        </tr>
        <tr>
            <td style="color: #dc3545; font-weight: bold;">F</td>
            <td>0-49</td>
            <td>{{ $gradeDistribution['F'] }}</td>
            <td>
                @if($data['summary']['total_animals'] > 0)
                    {{ number_format(($gradeDistribution['F'] / $data['summary']['total_animals']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
            <td>Needs Attention</td>
        </tr>
    </tbody>
</table>
@else
<p><strong>No performance data available for the selected period.</strong></p>
@endif