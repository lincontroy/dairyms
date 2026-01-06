<?php

namespace App\Http\Controllers;

use App\Models\MilkProduction;
use App\Models\Animal;
use Illuminate\Http\Request;

class MilkProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $milkProductions = MilkProduction::with('animal')
            ->latest()
            ->paginate(20);
        
        $todayTotal = MilkProduction::today()->sum('total_yield');
        $monthTotal = MilkProduction::currentMonth()->sum('total_yield');
        
        return view('milk-production.index', compact(
            'milkProductions', 
            'todayTotal', 
            'monthTotal'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $animals = Animal::where('status', 'lactating')
            ->where('is_active', true)
            ->get();
        
        $animalId = $request->get('animal_id');
        $selectedAnimal = $animalId ? Animal::find($animalId) : null;
        
        return view('milk-production.create', compact('animals', 'selectedAnimal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'date' => 'required|date',
            'morning_yield' => 'nullable|numeric|min:0|max:100',
            'afternoon_yield' => 'nullable|numeric|min:0|max:100', // Add this
            'evening_yield' => 'nullable|numeric|min:0|max:100',
            'lactation_number' => 'nullable|integer|min:1|max:10',
            'days_in_milk' => 'nullable|integer|min:1|max:400',
            'notes' => 'nullable|string|max:500',
        ]);

        // Add current user as milker
        $validated['milker_id'] = auth()->id();
        
        // Check for duplicate entry
        $existing = MilkProduction::where('animal_id', $validated['animal_id'])
            ->whereDate('date', $validated['date'])
            ->exists();
            
        if ($existing) {
            return back()
                ->withInput()
                ->with('error', 'Milk record already exists for this animal on this date.');
        }

        MilkProduction::create($validated);

        return redirect()->route('milk-production.index')
            ->with('success', 'Milk production record added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MilkProduction $milkProduction)
    {
        $milkProduction->load(['animal', 'milker']);
        return view('milk-production.show', compact('milkProduction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MilkProduction $milkProduction)
    {
        $animals = Animal::where('status', 'lactating')
            ->where('is_active', true)
            ->get();
            
        return view('milk-production.edit', compact('milkProduction', 'animals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MilkProduction $milkProduction)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'date' => 'required|date',
            'morning_yield' => 'nullable|numeric|min:0|max:100',
            'afternoon_yield' => 'nullable|numeric|min:0|max:100', // Add this
            'evening_yield' => 'nullable|numeric|min:0|max:100',
            'lactation_number' => 'nullable|integer|min:1|max:10',
            'days_in_milk' => 'nullable|integer|min:1|max:400',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for duplicate entry (excluding current record)
        $existing = MilkProduction::where('animal_id', $validated['animal_id'])
            ->whereDate('date', $validated['date'])
            ->where('id', '!=', $milkProduction->id)
            ->exists();
            
        if ($existing) {
            return back()
                ->withInput()
                ->with('error', 'Another milk record already exists for this animal on this date.');
        }

        $milkProduction->update($validated);

        return redirect()->route('milk-production.show', $milkProduction)
            ->with('success', 'Milk production record updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MilkProduction $milkProduction)
    {
        $milkProduction->delete();

        return redirect()->route('milk-production.index')
            ->with('success', 'Milk production record deleted successfully!');
    }

    /**
     * Quick milk entry for today
     */
    public function quickEntry()
    {

        // dd('quickEntry');
        $animals = Animal::where('status', 'lactating')
            ->where('is_active', true)
            ->get();
            
        return view('milk-production.quick-entry', compact('animals'));
    }

    /**
     * Store multiple milk records (quick entry)
     */
    public function storeMultiple(Request $request)
    {
        $records = $request->input('records', []);
        
        foreach ($records as $animalId => $record) {
            if (!empty($animalId) && 
                (!empty($record['morning_yield']) || 
                 !empty($record['afternoon_yield']) || 
                 !empty($record['evening_yield']))) {
                
                // Check for existing record
                $existing = MilkProduction::where('animal_id', $animalId)
                    ->whereDate('date', $request->date)
                    ->exists();

                if (!$existing) {
                    MilkProduction::create([
                        'animal_id' => $animalId,
                        'date' => $request->date,
                        'morning_yield' => $record['morning_yield'] ?? 0,
                        'afternoon_yield' => $record['afternoon_yield'] ?? 0,
                        'evening_yield' => $record['evening_yield'] ?? 0,
                        // Don't set total_yield - let the model or database handle it
                        'milker_id' => auth()->id(),
                        'notes' => $request->notes,
                    ]);
                }
            }
        }

        // Clear draft if exists
        if ($request->has('clear_draft')) {
            // You might want to clear localStorage on the client side
        }

        return redirect()->route('milk-production.index')
            ->with('success', 'Milk records added successfully!');
    }


    /**
     * Monthly report
     */
    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date = \Carbon\Carbon::createFromFormat('Y-m', $month);
        
        $milkProductions = MilkProduction::with('animal')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');
        
        $dailyTotals = [];
        $monthTotal = 0;
        
        foreach ($milkProductions as $date => $records) {
            $dailyTotal = $records->sum('total_yield');
            $dailyTotals[$date] = $dailyTotal;
            $monthTotal += $dailyTotal;
        }
        
        return view('milk-production.monthly-report', compact(
            'milkProductions',
            'dailyTotals',
            'monthTotal',
            'month'
        ));
    }
}