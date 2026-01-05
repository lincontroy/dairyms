<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\MilkProduction;
use App\Models\HealthRecord;
use App\Models\BreedingRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{
    public function index()
    {
        $reportTypes = [
            'animals' => 'Animal Registry',
            'milk_production' => 'Milk Production',
            'health_records' => 'Health Records',
            'breeding_records' => 'Breeding Records',
            'financial' => 'Financial Summary',
            'performance' => 'Animal Performance',
        ];

        return view('reports.index', compact('reportTypes'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'format' => 'nullable|in:html,pdf,excel',
        ]);

        $reportType = $request->report_type;
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        $format = $request->format ?? 'html';

        $data = $this->generateReportData($reportType, $startDate, $endDate, $request->all());

        if ($format === 'pdf') {
            return $this->generatePDF($reportType, $data, $startDate, $endDate);
        }

        if ($format === 'excel') {
            return Excel::download(new ReportsExport($reportType, $data), $this->getReportFileName($reportType, 'xlsx'));
        }

        return view('reports.view', compact('data', 'reportType', 'startDate', 'endDate'));
    }

    private function generateReportData($type, $startDate, $endDate, $filters = [])
    {
        switch ($type) {
            case 'animals':
                return $this->generateAnimalReport($startDate, $endDate, $filters);
            
            case 'milk_production':
                return $this->generateMilkProductionReport($startDate, $endDate, $filters);
            
            case 'health_records':
                return $this->generateHealthReport($startDate, $endDate, $filters);
            
            case 'breeding_records':
                return $this->generateBreedingReport($startDate, $endDate, $filters);
            
            case 'financial':
                return $this->generateFinancialReport($startDate, $endDate, $filters);
            
            case 'performance':
                return $this->generatePerformanceReport($startDate, $endDate, $filters);
            
            default:
                return [];
        }
    }

    private function generateAnimalReport($startDate, $endDate, $filters)
    {
        $query = Animal::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if (!empty($filters['breed'])) {
            $query->where('breed', $filters['breed']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['sex'])) {
            $query->where('sex', $filters['sex']);
        }

        $animals = $query->with(['dam', 'sire'])->get();

        $summary = [
            'total' => $animals->count(),
            'active' => $animals->where('is_active', true)->count(),
            'by_breed' => $animals->groupBy('breed')->map->count(),
            'by_status' => $animals->groupBy('status')->map->count(),
            'by_sex' => $animals->groupBy('sex')->map->count(),
        ];

        return [
            'animals' => $animals,
            'summary' => $summary,
            'filters' => $filters,
        ];
    }

    private function generateMilkProductionReport($startDate, $endDate, $filters)
    {
        $query = MilkProduction::query()->with(['animal', 'milker']);

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($filters['animal_id'])) {
            $query->where('animal_id', $filters['animal_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['approved'])) {
            $query->where('status', 'approved');
        }

        $records = $query->get();

        // Daily totals
        $dailyTotals = $records->groupBy('date')->map(function($dayRecords) {
            return [
                'morning' => $dayRecords->sum('morning_yield'),
                'evening' => $dayRecords->sum('evening_yield'),
                'total' => $dayRecords->sum('total_yield'),
                'count' => $dayRecords->count(),
            ];
        });

        // Animal-wise totals
        $animalTotals = $records->groupBy('animal_id')->map(function($animalRecords) {
            return [
                'animal' => $animalRecords->first()->animal,
                'total_milk' => $animalRecords->sum('total_yield'),
                'average_daily' => $animalRecords->avg('total_yield'),
                'records_count' => $animalRecords->count(),
            ];
        });

        $summary = [
            'total_records' => $records->count(),
            'total_milk' => $records->sum('total_yield'),
            'average_daily' => $records->groupBy('date')->count() > 0 
                ? $records->sum('total_yield') / $records->groupBy('date')->count()
                : 0,
            'approved_records' => $records->where('status', 'approved')->count(),
            'pending_records' => $records->where('status', 'pending')->count(),
        ];

        return [
            'records' => $records,
            'daily_totals' => $dailyTotals,
            'animal_totals' => $animalTotals,
            'summary' => $summary,
            'filters' => $filters,
        ];
    }

    private function generateHealthReport($startDate, $endDate, $filters)
    {
        $query = HealthRecord::query()->with('animal');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        if (!empty($filters['diagnosis'])) {
            $query->where('diagnosis', 'like', '%' . $filters['diagnosis'] . '%');
        }

        if (!empty($filters['outcome'])) {
            $query->where('outcome', $filters['outcome']);
        }

        if (!empty($filters['animal_id'])) {
            $query->where('animal_id', $filters['animal_id']);
        }

        $records = $query->get();

        $summary = [
            'total_records' => $records->count(),
            'by_diagnosis' => $records->groupBy('diagnosis')->map->count(),
            'by_outcome' => $records->groupBy('outcome')->map->count(),
            'under_treatment' => $records->where('outcome', 'Under Treatment')->count(),
            'recovered' => $records->where('outcome', 'Recovered')->count(),
        ];

        // Animal health history
        $animalHealth = $records->groupBy('animal_id')->map(function($animalRecords) {
            return [
                'animal' => $animalRecords->first()->animal,
                'total_treatments' => $animalRecords->count(),
                'latest_issue' => $animalRecords->sortByDesc('date')->first()->diagnosis,
                'under_treatment' => $animalRecords->where('outcome', 'Under Treatment')->count() > 0,
            ];
        });

        return [
            'records' => $records,
            'animal_health' => $animalHealth,
            'summary' => $summary,
            'filters' => $filters,
        ];
    }

    private function generateBreedingReport($startDate, $endDate, $filters)
    {
        $query = BreedingRecord::query()->with('animal');

        if ($startDate) {
            $query->whereDate('date_of_service', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date_of_service', '<=', $endDate);
        }

        if (!empty($filters['pregnancy_result'])) {
            $query->where('pregnancy_result', filter_var($filters['pregnancy_result'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['breeding_method'])) {
            $query->where('breeding_method', $filters['breeding_method']);
        }

        $records = $query->get();

        // Current pregnancies
        $currentPregnancies = $records
            ->where('pregnancy_result', true)
            ->whereNull('actual_calving_date')
            ->map(function($record) {
                $record->days_pregnant = $record->expected_calving_date 
                    ? now()->diffInDays($record->date_of_service)
                    : null;
                return $record;
            });

        $summary = [
            'total_services' => $records->count(),
            'successful_pregnancies' => $records->where('pregnancy_result', true)->count(),
            'success_rate' => $records->count() > 0 
                ? ($records->where('pregnancy_result', true)->count() / $records->count()) * 100
                : 0,
            'current_pregnancies' => $currentPregnancies->count(),
            'calvings_this_period' => $records->whereNotNull('actual_calving_date')->count(),
        ];

        // Breeding method statistics
        $methodStats = $records->groupBy('breeding_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'successful' => $group->where('pregnancy_result', true)->count(),
                'success_rate' => $group->count() > 0 
                    ? ($group->where('pregnancy_result', true)->count() / $group->count()) * 100
                    : 0,
            ];
        });

        return [
            'records' => $records,
            'current_pregnancies' => $currentPregnancies,
            'summary' => $summary,
            'method_stats' => $methodStats,
            'filters' => $filters,
        ];
    }

    private function generateFinancialReport($startDate, $endDate, $filters)
    {
        // This is a simplified financial report
        // You should expand this with actual financial data
        
        $milkProduction = MilkProduction::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->get();

        $milkRevenue = $milkProduction->sum('total_yield') * 50; // Assuming 50 per liter

        $animalsSold = Animal::where('is_active', false)
            ->whereBetween('deleted_at', [$startDate, $endDate])
            ->count();

        $animalsSoldRevenue = $animalsSold * 50000; // Assuming 50,000 per animal

        $totalRevenue = $milkRevenue + $animalsSoldRevenue;

        // Expenses (simplified - you should add actual expense tracking)
        $expenses = [
            'feed' => 150000,
            'veterinary' => 50000,
            'labor' => 100000,
            'utilities' => 30000,
            'other' => 20000,
        ];

        $totalExpenses = array_sum($expenses);
        $netProfit = $totalRevenue - $totalExpenses;

        return [
            'revenue' => [
                'milk' => $milkRevenue,
                'animals_sold' => $animalsSoldRevenue,
                'total' => $totalRevenue,
            ],
            'expenses' => $expenses,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'profit_margin' => $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];
    }

    private function generatePerformanceReport($startDate, $endDate, $filters)
    {
        $animals = Animal::with([
            'milkProductions' => function($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                }
                $query->where('status', 'approved');
            },
            'healthRecords' => function($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                }
            }
        ])->get();

        $performanceData = $animals->map(function($animal) {
            $totalMilk = $animal->milkProductions->sum('total_yield');
            $milkDays = $animal->milkProductions->groupBy('date')->count();
            $avgDailyMilk = $milkDays > 0 ? $totalMilk / $milkDays : 0;

            $healthIssues = $animal->healthRecords->count();
            $treatmentCost = $animal->healthRecords->sum(function($record) {
                // Assuming treatment cost calculation
                return 1000; // Placeholder
            });

            return [
                'animal' => $animal,
                'total_milk' => $totalMilk,
                'avg_daily_milk' => $avgDailyMilk,
                'milk_days' => $milkDays,
                'health_issues' => $healthIssues,
                'treatment_cost' => $treatmentCost,
                'performance_score' => $this->calculatePerformanceScore($totalMilk, $healthIssues),
            ];
        })->sortByDesc('performance_score');

        $summary = [
            'total_animals' => $animals->count(),
            'top_performers' => $performanceData->take(10)->values(),
            'avg_milk_per_animal' => $performanceData->avg('total_milk'),
            'avg_health_issues' => $performanceData->avg('health_issues'),
        ];

        return [
            'performance_data' => $performanceData,
            'summary' => $summary,
            'filters' => $filters,
        ];
    }

    private function calculatePerformanceScore($milkTotal, $healthIssues)
    {
        $milkScore = min($milkTotal / 1000, 100); // Normalize milk production
        $healthPenalty = min($healthIssues * 10, 50); // Penalty for health issues
        
        return max(0, $milkScore - $healthPenalty);
    }

    private function generatePDF($reportType, $data, $startDate, $endDate)
    {
        $viewName = 'reports.partials.pdf.' . $reportType;
        
        // Check if the specific PDF view exists, otherwise use a generic one
        if (!view()->exists($viewName)) {
            $viewName = 'reports.pdf.generic';
        }
        
        $pdf = Pdf::loadView('reports.pdf', [
            'data' => $data,
            'reportType' => $reportType,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now(),
            'contentView' => $viewName,
        ]);
    
        $fileName = $this->getReportFileName($reportType, 'pdf');
    
        return $pdf->download($fileName);
    }

    private function getReportFileName($reportType, $extension)
    {
        $typeNames = [
            'animals' => 'animal_registry',
            'milk_production' => 'milk_production',
            'health_records' => 'health_records',
            'breeding_records' => 'breeding_records',
            'financial' => 'financial_report',
            'performance' => 'animal_performance',
        ];

        $name = $typeNames[$reportType] ?? 'report';
        return $name . '_' . date('Y-m-d') . '.' . $extension;
    }
}