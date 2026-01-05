<div class="summary">
    <h3>Financial Summary</h3>
    <table>
        <tr>
            <td><strong>Report Period:</strong></td>
            <td>
                {{ $data['period']['start'] ? $data['period']['start']->format('M d, Y') : 'Beginning' }} 
                to {{ $data['period']['end']->format('M d, Y') }}
            </td>
        </tr>
    </table>
</div>

<h3>Revenue Breakdown</h3>
<table>
    <thead>
        <tr>
            <th>Revenue Source</th>
            <th>Amount (KSH)</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Milk Sales</td>
            <td>{{ number_format($data['revenue']['milk'], 2) }}</td>
            <td>
                @if($data['revenue']['total'] > 0)
                    {{ number_format(($data['revenue']['milk'] / $data['revenue']['total']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        <tr>
            <td>Animal Sales</td>
            <td>{{ number_format($data['revenue']['animals_sold'], 2) }}</td>
            <td>
                @if($data['revenue']['total'] > 0)
                    {{ number_format(($data['revenue']['animals_sold'] / $data['revenue']['total']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        <tr style="font-weight: bold; background-color: #e9ecef;">
            <td>Total Revenue</td>
            <td>{{ number_format($data['revenue']['total'], 2) }}</td>
            <td>100%</td>
        </tr>
    </tbody>
</table>

<h3>Expense Breakdown</h3>
<table>
    <thead>
        <tr>
            <th>Expense Category</th>
            <th>Amount (KSH)</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['expenses'] as $category => $amount)
        <tr>
            <td>{{ ucfirst($category) }}</td>
            <td>{{ number_format($amount, 2) }}</td>
            <td>
                @if($data['total_expenses'] > 0)
                    {{ number_format(($amount / $data['total_expenses']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        @endforeach
        <tr style="font-weight: bold; background-color: #e9ecef;">
            <td>Total Expenses</td>
            <td>{{ number_format($data['total_expenses'], 2) }}</td>
            <td>100%</td>
        </tr>
    </tbody>
</table>

<h3>Profit & Loss Statement</h3>
<table>
    <tbody>
        <tr>
            <td><strong>Total Revenue:</strong></td>
            <td style="text-align: right;">{{ number_format($data['revenue']['total'], 2) }} KSH</td>
        </tr>
        <tr>
            <td><strong>Total Expenses:</strong></td>
            <td style="text-align: right;">{{ number_format($data['total_expenses'], 2) }} KSH</td>
        </tr>
        <tr style="border-top: 2px solid #333;">
            <td><strong>Net Profit/Loss:</strong></td>
            <td style="text-align: right; font-weight: bold; 
                @if($data['net_profit'] >= 0)
                    color: #28a745;
                @else
                    color: #dc3545;
                @endif">
                {{ number_format($data['net_profit'], 2) }} KSH
            </td>
        </tr>
        <tr>
            <td><strong>Profit Margin:</strong></td>
            <td style="text-align: right; font-weight: bold;
                @if($data['profit_margin'] >= 0)
                    color: #28a745;
                @else
                    color: #dc3545;
                @endif">
                {{ number_format($data['profit_margin'], 1) }}%
            </td>
        </tr>
    </tbody>
</table>

<h3>Key Financial Ratios</h3>
<table>
    <tbody>
        <tr>
            <td><strong>Revenue per Liter of Milk:</strong></td>
            <td style="text-align: right;">
                @php
                    $milkRevenuePerLiter = $data['revenue']['milk'] > 0 && isset($data['milk_liters']) 
                        ? $data['revenue']['milk'] / $data['milk_liters'] 
                        : 0;
                @endphp
                {{ number_format($milkRevenuePerLiter, 2) }} KSH/L
            </td>
        </tr>
        <tr>
            <td><strong>Expense to Revenue Ratio:</strong></td>
            <td style="text-align: right;">
                @if($data['revenue']['total'] > 0)
                    {{ number_format(($data['total_expenses'] / $data['revenue']['total']) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Operating Efficiency:</strong></td>
            <td style="text-align: right;">
                @if($data['revenue']['total'] > 0)
                    {{ number_format((1 - ($data['total_expenses'] / $data['revenue']['total'])) * 100, 1) }}%
                @else
                    0%
                @endif
            </td>
        </tr>
    </tbody>
</table>