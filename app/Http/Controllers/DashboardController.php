<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\BreedingRecord;
use App\Models\MilkProduction;
use App\Models\HealthRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Get all animals for statistics
        $allAnimals = Animal::with(['breedingRecords', 'milkProductions', 'healthRecords'])->get();
        
        // Statistics
        $stats = [
            'totalAnimals' => $allAnimals->count(),
            'lactatingCows' => $allAnimals->where('status', 'lactating')->count(),
            'pregnantCows' => $allAnimals->where('status', 'pregnant')->count(),
            'dueForCalving' => BreedingRecord::where('expected_calving_date', '>=', now())
                ->where('actual_calving_date', null)
                ->count(),
            'dryCows' => $allAnimals->where('status', 'dry')->count(),
            'totalMilkToday' => MilkProduction::whereDate('date', today())->sum('total_yield'),
            'sickAnimals' => HealthRecord::where('date', '>=', now()->subDays(7))
                ->where('outcome', 'Under Treatment')
                ->distinct('animal_id')
                ->count('animal_id'),
        ];

        // Recent activities
        $recentAnimals = Animal::latest()->take(5)->get();
        $recentBreedings = BreedingRecord::with('animal')->latest()->take(5)->get();
        $recentHealth = HealthRecord::with('animal')->where('date', '>=', now()->subDays(30))
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'stats', 
            'recentAnimals', 
            'recentBreedings', 
            'recentHealth',
            'user',
            'allAnimals' // Pass all animals for statistics
        ));
    }
}