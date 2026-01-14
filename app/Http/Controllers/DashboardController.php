<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\MilkProduction;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\DB;
use App\Models\BreedingRecord;
use App\Models\Supplier;
use App\Models\MilkSupply;
use App\Models\SupplierPayment;
use App\Models\Expense; // Added Expense model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get all animals for statistics
        $allAnimals = Animal::with(['breedingRecords', 'milkProductions', 'healthRecords'])->get();
        
        // Basic animal statistics
        $stats = [
            'totalAnimals' => $allAnimals->count(),
            'lactatingCows' => $allAnimals->where('status', 'lactating')->count(),
            'pregnantCows' => $allAnimals->where('status', 'pregnant')->count(),
            'dueForCalving' => BreedingRecord::where('expected_calving_date', '>=', now())
                ->where('actual_calving_date', null)
                ->count(),
            'dryCows' => $allAnimals->where('status', 'dry')->count(),
            'totalMilkToday' => MilkProduction::whereDate('date', today())
                ->sum(DB::raw('COALESCE(morning_yield, 0) + COALESCE(afternoon_yield, 0) + COALESCE(evening_yield, 0)')),
            'sickAnimals' => HealthRecord::where('date', '>=', now()->subDays(7))
                ->where('outcome', 'Under Treatment')
                ->distinct('animal_id')
                ->count('animal_id'),
        ];

        // Milk Supply & Supplier Statistics
        $today = today()->format('Y-m-d');
        $todaySupplied = MilkSupply::whereDate('date', $today)->sum('quantity_liters');
        $todayWaste = MilkSupply::whereDate('date', $today)->sum('waste_liters');
        $todayRevenue = MilkSupply::whereDate('date', $today)->sum('total_amount');
        
        $monthStart = now()->startOfMonth()->format('Y-m-d');
        $monthEnd = now()->endOfMonth()->format('Y-m-d');
        $monthRevenue = MilkSupply::whereBetween('date', [$monthStart, $monthEnd])
            ->where('status', 'approved')
            ->sum('total_amount');
        
        $pendingPaymentsCount = SupplierPayment::where('status', 'pending')->count();
        $pendingPaymentsAmount = SupplierPayment::where('status', 'pending')->sum('amount');
        
        // Calculate total balance due more efficiently
        $suppliers = Supplier::with(['milkSupplies' => function($q) {
            $q->where('status', 'approved');
        }, 'payments' => function($q) {
            $q->where('status', 'approved');
        }])->get();
        
        $totalBalanceDue = 0;
        foreach ($suppliers as $supplier) {
            $totalSupplied = $supplier->milkSupplies->sum('total_amount');
            $totalPaid = $supplier->payments->sum('amount');
            $totalBalanceDue += ($totalSupplied - $totalPaid);
        }
        
        $milkSupplyStats = [
            'totalSuppliers' => Supplier::where('status', 'active')->count(),
            'todaySupplied' => $todaySupplied,
            'todayWaste' => $todayWaste,
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'pendingPaymentsAmount' => $pendingPaymentsAmount,
            'totalBalanceDue' => $totalBalanceDue,
            'avgMilkPrice' => Supplier::where('status', 'active')->avg('rate_per_liter') ?? 0,
        ];

        // Get expense statistics
        $expenseStats = $this->getExpenseStats();

        // Recent activities
        $recentAnimals = Animal::latest()->take(5)->get();
        $recentBreedings = BreedingRecord::with('animal')->latest()->take(5)->get();
        $recentHealth = HealthRecord::with('animal')->where('date', '>=', now()->subDays(30))
            ->latest()
            ->take(5)
            ->get();
        
        // Recent milk supplies
        $recentMilkSupplies = MilkSupply::with('supplier')
            ->latest()
            ->take(5)
            ->get();
        
        // Recent payments
        $recentPayments = SupplierPayment::with('supplier')
            ->latest()
            ->take(5)
            ->get();
        
        // Recent expenses (only for users who can manage expenses)
        $recentExpenses = collect();
        if ($user->canManageExpenses()) {
            $recentExpenses = Expense::with(['user', 'supplier'])
                ->latest()
                ->take(5)
                ->get();
        }
        
        // Top suppliers this month
        $topSuppliers = Supplier::with(['milkSupplies' => function($q) {
            $q->whereMonth('date', now()->month)
              ->where('status', 'approved');
        }, 'payments' => function($q) {
            $q->where('status', 'approved');
        }])->get()->sortByDesc(function($supplier) {
            return $supplier->milkSupplies->sum('quantity_liters');
        })->take(5);
        
        // Pending tasks - Add expense pending tasks
        $pendingTasks = [
            'pendingMilkSupplies' => MilkSupply::where('status', 'recorded')->count(),
            'pendingPayments' => SupplierPayment::where('status', 'pending')->count(),
            'pendingExpenses' => Expense::where('status', 'pending')->count(),
            'lowActivitySuppliers' => Supplier::where('status', 'active')
                ->whereHas('milkSupplies', function($q) {
                    $q->whereDate('date', '>=', now()->subDays(7));
                }, '<', 3)
                ->count(),
            'activeHealthIssues' => HealthRecord::where('outcome', 'Under Treatment')->count(),
        ];

        // Calculate net profit/loss for admin
        $financialOverview = [];
        if ($user->canViewExpenseTotals()) {
            $currentMonthRevenue = $milkSupplyStats['monthRevenue'];
            $currentMonthExpenses = $expenseStats['month_to_date'];
            $netProfit = $currentMonthRevenue - $currentMonthExpenses;
            
            $financialOverview = [
                'month_revenue' => $currentMonthRevenue,
                'month_expenses' => $currentMonthExpenses,
                'net_profit' => $netProfit,
                'profit_margin' => $currentMonthRevenue > 0 ? 
                    ($netProfit / $currentMonthRevenue) * 100 : 0,
            ];
        }

        return view('dashboard.index', compact(
            'stats', 
            'milkSupplyStats',
            'expenseStats',
            'financialOverview',
            'recentAnimals', 
            'recentBreedings', 
            'recentHealth',
            'recentMilkSupplies',
            'recentPayments',
            'recentExpenses',
            'user',
            'allAnimals',
            'topSuppliers',
            'pendingTasks'
        ));
    }

    // Expense statistics method
    private function getExpenseStats()
    {
        if (!auth()->user()->canViewExpenseTotals()) {
            return [
                'today' => 0,
                'month_to_date' => 0,
                'pending_count' => 0,
                'top_categories' => collect()
            ];
        }

        $today = now()->toDateString();
        $monthStart = now()->startOfMonth();

        return [
            'today' => Expense::approved()->forDate($today)->sum('amount'),
            'month_to_date' => Expense::approved()->whereDate('date', '>=', $monthStart)->sum('amount'),
            'pending_count' => Expense::pending()->count(),
            'top_categories' => Expense::approved()
                ->whereDate('date', '>=', $monthStart)
                ->selectRaw('category, SUM(amount) as total')
                ->groupBy('category')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get()
        ];
    }
}