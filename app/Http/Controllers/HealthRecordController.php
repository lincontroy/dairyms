<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\Animal;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $healthRecords = HealthRecord::with('animal')
            ->latest()
            ->paginate(20);
            
        $activeIssues = HealthRecord::where('outcome', 'Under Treatment')->count();
        $recoveredThisMonth = HealthRecord::where('outcome', 'Recovered')
            ->whereMonth('date', now()->month)
            ->count();
            
        return view('health-records.index', compact('healthRecords', 'activeIssues', 'recoveredThisMonth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $animals = Animal::where('is_active', true)->get();
        $animalId = $request->get('animal_id');
        $selectedAnimal = $animalId ? Animal::find($animalId) : null;
        
        return view('health-records.create', compact('animals', 'selectedAnimal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'date' => 'required|date',
            'diagnosis' => 'required|string|max:255',
            'clinical_signs' => 'nullable|string',
            'treatment' => 'required|string',
            'drug_name' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:100',
            'route' => 'nullable|string|max:50',
            'duration' => 'nullable|string|max:50',
            'milk_withdrawal_days' => 'nullable|integer|min:0|max:365',
            'meat_withdrawal_days' => 'nullable|integer|min:0|max:365',
            'veterinarian' => 'nullable|string|max:255',
            'outcome' => 'required|in:Recovered,Under Treatment,Not Responding,Died',
            'notes' => 'nullable|string'
        ]);

        HealthRecord::create($validated);

        return redirect()->route('health-records.index')
            ->with('success', 'Health record added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(HealthRecord $healthRecord)
    {
        $healthRecord->load('animal');
        return view('health-records.show', compact('healthRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HealthRecord $healthRecord)
    {
        $animals = Animal::where('is_active', true)->get();
        return view('health-records.edit', compact('healthRecord', 'animals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HealthRecord $healthRecord)
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'date' => 'required|date',
            'diagnosis' => 'required|string|max:255',
            'clinical_signs' => 'nullable|string',
            'treatment' => 'required|string',
            'drug_name' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:100',
            'route' => 'nullable|string|max:50',
            'duration' => 'nullable|string|max:50',
            'milk_withdrawal_days' => 'nullable|integer|min:0|max:365',
            'meat_withdrawal_days' => 'nullable|integer|min:0|max:365',
            'veterinarian' => 'nullable|string|max:255',
            'outcome' => 'required|in:Recovered,Under Treatment,Not Responding,Died',
            'notes' => 'nullable|string'
        ]);

        $healthRecord->update($validated);

        return redirect()->route('health-records.show', $healthRecord)
            ->with('success', 'Health record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HealthRecord $healthRecord)
    {
        $healthRecord->delete();

        return redirect()->route('health-records.index')
            ->with('success', 'Health record deleted successfully!');
    }

    /**
     * Show active health issues
     */
    public function activeIssues()
    {
        $healthRecords = HealthRecord::with('animal')
            ->where('outcome', 'Under Treatment')
            ->latest()
            ->paginate(20);
            
        return view('health-records.active', compact('healthRecords'));
    }

    /**
     * Monthly health report
     */
   /**
 * Monthly health report
 */
public function monthlyReport(Request $request)
{
    $month = $request->get('month', now()->format('Y-m'));
    $date = \Carbon\Carbon::createFromFormat('Y-m', $month);
    
    $healthRecords = HealthRecord::with('animal')
        ->whereYear('date', $date->year)
        ->whereMonth('date', $date->month)
        ->latest()
        ->get();
        
    $stats = [
        'total' => $healthRecords->count(),
        'recovered' => $healthRecords->where('outcome', 'Recovered')->count(),
        'under_treatment' => $healthRecords->where('outcome', 'Under Treatment')->count(),
        'not_responding' => $healthRecords->where('outcome', 'Not Responding')->count(),
        'died' => $healthRecords->where('outcome', 'Died')->count(),
    ];
    
    return view('health-records.monthly-report', compact('healthRecords', 'stats', 'month'));
}
}