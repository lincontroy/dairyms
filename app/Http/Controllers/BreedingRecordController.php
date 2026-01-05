<?php

namespace App\Http\Controllers;

use App\Models\BreedingRecord;
use App\Models\Animal;
use Illuminate\Http\Request;

class BreedingRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breedingRecords = BreedingRecord::with('animal')
            ->latest()
            ->paginate(20);
            
        $pregnantCows = BreedingRecord::where('pregnancy_result', true)
            ->whereNull('actual_calving_date')
            ->count();
            
        $pendingDiagnosis = BreedingRecord::whereNull('pregnancy_result')
            ->count();
            
        return view('breeding-records.index', compact(
            'breedingRecords', 
            'pregnantCows', 
            'pendingDiagnosis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $animals = Animal::where('sex', 'Female')
            ->where('status', '!=', 'calf')
            ->where('is_active', true)
            ->get();
            
        $animalId = $request->get('animal_id');
        $selectedAnimal = $animalId ? Animal::find($animalId) : null;
        
        return view('breeding-records.create', compact('animals', 'selectedAnimal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'date_of_service' => 'required|date',
            'breeding_method' => 'required|in:Natural,AI,Synchronization',
            'bull_semen_id' => 'nullable|string|max:100',
            'technician' => 'nullable|string|max:255',
            'pregnancy_diagnosis_date' => 'nullable|date|after_or_equal:date_of_service',
            'pregnancy_result' => 'nullable|boolean',
            'expected_calving_date' => 'nullable|date|after:date_of_service',
            'actual_calving_date' => 'nullable|date|after:date_of_service',
            'calving_outcome' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        // Auto-calculate expected calving date if not provided (283 days average)
        if (empty($validated['expected_calving_date']) && $validated['date_of_service']) {
            $dateOfService = \Carbon\Carbon::parse($validated['date_of_service']);
            $validated['expected_calving_date'] = $dateOfService->addDays(283);
        }

        BreedingRecord::create($validated);

        return redirect()->route('breeding-records.index')
            ->with('success', 'Breeding record added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BreedingRecord $breedingRecord)
    {
        $breedingRecord->load('animal');
        return view('breeding-records.show', compact('breedingRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BreedingRecord $breedingRecord)
    {
        $animals = Animal::where('sex', 'Female')
            ->where('status', '!=', 'calf')
            ->where('is_active', true)
            ->get();
            
        return view('breeding-records.edit', compact('breedingRecord', 'animals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BreedingRecord $breedingRecord)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'date_of_service' => 'required|date',
            'breeding_method' => 'required|in:Natural,AI,Synchronization',
            'bull_semen_id' => 'nullable|string|max:100',
            'technician' => 'nullable|string|max:255',
            'pregnancy_diagnosis_date' => 'nullable|date|after_or_equal:date_of_service',
            'pregnancy_result' => 'nullable|boolean',
            'expected_calving_date' => 'nullable|date|after:date_of_service',
            'actual_calving_date' => 'nullable|date|after:date_of_service',
            'calving_outcome' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        // Auto-calculate expected calving date if not provided
        if (empty($validated['expected_calving_date']) && $validated['date_of_service'] && $validated['pregnancy_result'] === true) {
            $dateOfService = \Carbon\Carbon::parse($validated['date_of_service']);
            $validated['expected_calving_date'] = $dateOfService->addDays(283);
        }

        $breedingRecord->update($validated);

        return redirect()->route('breeding-records.show', $breedingRecord)
            ->with('success', 'Breeding record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreedingRecord $breedingRecord)
    {
        $breedingRecord->delete();

        return redirect()->route('breeding-records.index')
            ->with('success', 'Breeding record deleted successfully!');
    }

    /**
     * Show pregnant animals
     */
    public function pregnantAnimals()
    {
        $breedingRecords = BreedingRecord::with('animal')
            ->where('pregnancy_result', true)
            ->whereNull('actual_calving_date')
            ->latest()
            ->paginate(20);
            
        return view('breeding-records.pregnant', compact('breedingRecords'));
    }

    /**
     * Show animals due for calving soon
     */
    public function dueForCalving()
    {
        $breedingRecords = BreedingRecord::with('animal')
            ->where('pregnancy_result', true)
            ->whereNull('actual_calving_date')
            ->where('expected_calving_date', '<=', now()->addDays(30))
            ->where('expected_calving_date', '>=', now())
            ->orderBy('expected_calving_date')
            ->paginate(20);
            
        return view('breeding-records.due-for-calving', compact('breedingRecords'));
    }

    /**
     * Show breeding calendar
     */
    public function breedingCalendar(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date = \Carbon\Carbon::createFromFormat('Y-m', $month);
        
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        // Get breeding events for the month
        $breedingRecords = BreedingRecord::with('animal')
            ->where(function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date_of_service', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('expected_calving_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('actual_calving_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('pregnancy_diagnosis_date', [$startOfMonth, $endOfMonth]);
            })
            ->get()
            ->groupBy(function($record) use ($date) {
                if ($record->date_of_service->format('Y-m') == $date->format('Y-m')) {
                    return 'breeding';
                } elseif ($record->expected_calving_date && $record->expected_calving_date->format('Y-m') == $date->format('Y-m')) {
                    return 'expected_calving';
                } elseif ($record->actual_calving_date && $record->actual_calving_date->format('Y-m') == $date->format('Y-m')) {
                    return 'actual_calving';
                } elseif ($record->pregnancy_diagnosis_date && $record->pregnancy_diagnosis_date->format('Y-m') == $date->format('Y-m')) {
                    return 'pregnancy_check';
                }
                return 'other';
            });
            
        return view('breeding-records.calendar', compact('breedingRecords', 'month'));
    }

    /**
     * Breeding performance report
     */
    public function performanceReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $breedingRecords = BreedingRecord::with('animal')
            ->whereYear('date_of_service', $year)
            ->get();
            
        $stats = [
            'total_services' => $breedingRecords->count(),
            'confirmed_pregnant' => $breedingRecords->where('pregnancy_result', true)->count(),
            'confirmed_not_pregnant' => $breedingRecords->where('pregnancy_result', false)->count(),
            'pending_diagnosis' => $breedingRecords->where('pregnancy_result', null)->count(),
            'successful_calvings' => $breedingRecords->whereNotNull('actual_calving_date')->count(),
            'conception_rate' => $breedingRecords->count() > 0 ? 
                ($breedingRecords->where('pregnancy_result', true)->count() / $breedingRecords->count()) * 100 : 0,
        ];
        
        // Group by month
        $monthlyStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthRecords = $breedingRecords->filter(function($record) use ($year, $i) {
                return $record->date_of_service->year == $year && $record->date_of_service->month == $i;
            });
            
            $monthlyStats[$i] = [
                'services' => $monthRecords->count(),
                'pregnant' => $monthRecords->where('pregnancy_result', true)->count(),
                'rate' => $monthRecords->count() > 0 ? 
                    ($monthRecords->where('pregnancy_result', true)->count() / $monthRecords->count()) * 100 : 0
            ];
        }
        
        return view('breeding-records.performance-report', compact(
            'breedingRecords', 
            'stats', 
            'monthlyStats', 
            'year'
        ));
    }
}