<?php

namespace App\Http\Controllers;

use App\Models\Calf;
use App\Models\Animal;
use App\Models\BreedingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CalfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Calf::with(['dam', 'sire', 'recordedBy'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('calf_id', 'like', "%{$search}%")
                  ->orWhere('ear_tag', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by sex
        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        // Filter by dam
        if ($request->filled('dam_id')) {
            $query->where('dam_id', $request->dam_id);
        }

        // Filter by weaning status
        if ($request->filled('is_weaned')) {
            $query->where('is_weaned', $request->is_weaned);
        }

        // Filter by special care
        if ($request->filled('requires_special_care')) {
            $query->where('requires_special_care', $request->requires_special_care);
        }

        $calves = $query->paginate(20);
        $dams = Animal::where('sex', 'female')
            ->whereIn('status', ['lactating', 'dry', 'pregnant'])
            ->get();
        
        return view('calves.index', compact('calves', 'dams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get dams (female animals)
        $dams = Animal::where('sex', 'female')
            ->whereIn('status', ['lactating', 'dry', 'pregnant'])
            ->orderBy('name')
            ->get();
    
        // Get sires (male animals)
        $sires = Animal::where('sex', 'male')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    
        // Get breeding records that might be related
        $breedingRecords = BreedingRecord::where('pregnancy_result', true)
            ->whereNotNull('expected_calving_date')
            ->with('animal') // Changed from 'dam' to 'animal'
            ->get();
    
        // Pre-select dam if coming from animal or breeding record
        $selectedDam = null;
        $selectedBreedingRecord = null;
        
        if ($request->filled('dam_id')) {
            $selectedDam = Animal::find($request->dam_id);
        }
        
        if ($request->filled('breeding_record_id')) {
            $selectedBreedingRecord = BreedingRecord::with('animal')->find($request->breeding_record_id);
        }
    
        return view('calves.create', compact(
            'dams', 
            'sires', 
            'breedingRecords',
            'selectedDam',
            'selectedBreedingRecord'
        ));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dam_id' => 'required|exists:animals,id',
            'sire_id' => 'nullable|exists:animals,id',
            'breeding_record_id' => 'nullable|exists:breeding_records,id',
            'name' => 'nullable|string|max:100',
            'ear_tag' => 'required|string|max:50|unique:calves,ear_tag',
            'sex' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'birth_weight' => 'nullable|numeric|min:0|max:100',
            'birth_type' => 'required|in:single,twin,triplet',
            'delivery_type' => 'required|in:normal,assisted,caesarean',
            'health_status' => 'required|in:excellent,good,fair,poor',
            'color_markings' => 'nullable|string|max:255',
            'vaccination_status' => 'required|in:pending,partial,complete',
            'requires_special_care' => 'boolean',
            'special_care_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
    
        DB::beginTransaction();
        try {
            $calf = Calf::create(array_merge($validated, [
                'recorded_by' => Auth::id(),
                'date_recorded' => now(),
            ]));
    
            // Update breeding record if linked
            if ($calf->breeding_record_id) {
                $breedingRecord = BreedingRecord::find($calf->breeding_record_id);
                if ($breedingRecord) {
                    // Calculate number of calves based on birth type
                    $calvesBorn = match($calf->birth_type) {
                        'single' => 1,
                        'twin' => 2,
                        'triplet' => 3,
                        default => 1,
                    };
                    
                    // Determine how many are alive based on health status
                    $calvesAlive = ($calf->health_status == 'poor') ? 0 : $calvesBorn;
                    
                    $breedingRecord->update([
                        'actual_calving_date' => $calf->date_of_birth,
                        'calves_born' => $calvesBorn,
                        'calves_alive' => $calvesAlive,
                        // You might also want to update calving_outcome
                        'calving_outcome' => $calf->delivery_type == 'normal' ? 'Normal' : 'Assisted',
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()->route('calves.show', $calf)
                ->with('success', 'Calf recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record calf: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Calf $calf)
    {
        // Eager load all necessary relationships
        $calf->load([
            'dam', 
            'sire', 
            'breedingRecord.dam', // or 'breedingRecord.animal' depending on your relationship name
            'recordedBy'
        ]);
        
        // Get siblings (calves from same dam born within same period)
        $siblings = Calf::where('dam_id', $calf->dam_id)
            ->where('id', '!=', $calf->id)
            ->whereBetween('date_of_birth', [
                $calf->date_of_birth->copy()->subDays(30),
                $calf->date_of_birth->copy()->addDays(30)
            ])
            ->with(['dam', 'sire'])
            ->get();
    
        // Get statistics
        $stats = [
            'age_in_days' => $calf->age_in_days,
            'age_in_months' => $calf->age_in_months,
            'is_weaning_due' => !$calf->is_weaned && $calf->weaning_date && $calf->weaning_date <= now(),
        ];
    
        return view('calves.show', compact('calf', 'siblings', 'stats'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calf $calf)
    {
        $dams = Animal::where('sex', 'female')
            ->whereIn('status', ['lactating', 'dry', 'pregnant'])
            ->orderBy('name')
            ->get();
    
        $sires = Animal::where('sex', 'male')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    
        // Get breeding records for the calf's dam OR the current breeding record
        $breedingRecords = BreedingRecord::where('animal_id', $calf->dam_id) // Use animal_id, not dam_id
            ->orWhere('id', $calf->breeding_record_id)
            ->with('animal')
            ->get();
    
        return view('calves.edit', compact('calf', 'dams', 'sires', 'breedingRecords'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calf $calf)
    {
        $validated = $request->validate([
            'dam_id' => 'required|exists:animals,id',
            'sire_id' => 'nullable|exists:animals,id',
            'breeding_record_id' => 'nullable|exists:breeding_records,id',
            'name' => 'nullable|string|max:100',
            'ear_tag' => 'required|string|max:50|unique:calves,ear_tag,' . $calf->id,
            'sex' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'birth_weight' => 'nullable|numeric|min:0|max:100',
            'birth_type' => 'required|in:single,twin,triplet',
            'delivery_type' => 'required|in:normal,assisted,caesarean',
            'health_status' => 'required|in:excellent,good,fair,poor',
            'status' => 'required|in:alive,dead,sold,transferred',
            'color_markings' => 'nullable|string|max:255',
            'vaccination_status' => 'required|in:pending,partial,complete',
            'weaning_date' => 'nullable|date',
            'weaning_weight' => 'nullable|numeric|min:0|max:500',
            'is_weaned' => 'boolean',
            'requires_special_care' => 'boolean',
            'special_care_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $calf->update($validated);

            DB::commit();

            return redirect()->route('calves.show', $calf)
                ->with('success', 'Calf updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update calf: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calf $calf)
    {
        DB::beginTransaction();
        try {
            $calf->delete();
            
            DB::commit();
            
            return redirect()->route('calves.index')
                ->with('success', 'Calf deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete calf: ' . $e->getMessage());
        }
    }

    /**
     * Mark calf as weaned.
     */
    public function markWeaned(Request $request, Calf $calf)
    {
        $request->validate([
            'weaning_weight' => 'required|numeric|min:0|max:500',
        ]);

        $calf->markAsWeaned($request->weaning_weight);

        return redirect()->route('calves.show', $calf)
            ->with('success', 'Calf marked as weaned successfully!');
    }

    /**
     * Update calf health status.
     */
    public function updateHealthStatus(Request $request, Calf $calf)
    {
        $request->validate([
            'health_status' => 'required|in:excellent,good,fair,poor',
            'notes' => 'nullable|string',
        ]);

        $calf->updateHealthStatus($request->health_status);

        if ($request->filled('notes')) {
            $calf->update(['notes' => $calf->notes . "\n" . now()->format('Y-m-d') . ': ' . $request->notes]);
        }

        return redirect()->route('calves.show', $calf)
            ->with('success', 'Health status updated successfully!');
    }

    /**
     * Record calf death.
     */
    public function recordDeath(Request $request, Calf $calf)
    {
        $request->validate([
            'death_date' => 'required|date',
            'cause_of_death' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $calf->recordDeath();
            
            // Add death details to notes
            $deathNote = "\n\nDEATH RECORDED: " . now()->format('Y-m-d H:i:s');
            $deathNote .= "\nDate of Death: " . $request->death_date;
            $deathNote .= "\nCause: " . $request->cause_of_death;
            $deathNote .= "\nNotes: " . ($request->notes ?? 'N/A');
            
            $calf->update([
                'notes' => $calf->notes . $deathNote,
            ]);

            DB::commit();

            return redirect()->route('calves.show', $calf)
                ->with('success', 'Calf death recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record death: ' . $e->getMessage());
        }
    }

    /**
     * Get calf statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_calves' => Calf::count(),
            'alive_calves' => Calf::alive()->count(),
            'dead_calves' => Calf::dead()->count(),
            'sold_calves' => Calf::sold()->count(),
            'male_calves' => Calf::where('sex', 'male')->count(),
            'female_calves' => Calf::where('sex', 'female')->count(),
            'weaned_calves' => Calf::weaned()->count(),
            'calves_needing_weaning' => Calf::needingWeaning()->count(),
            'calves_requiring_special_care' => Calf::requiringSpecialCare()->count(),
            'calves_born_this_month' => Calf::bornThisMonth()->count(),
            'calves_born_last_30_days' => Calf::bornLast30Days()->count(),
        ];

        // Age distribution
        $ageDistribution = [
            '0-30 days' => Calf::where('date_of_birth', '>=', now()->subDays(30))->count(),
            '1-3 months' => Calf::whereBetween('date_of_birth', [now()->subMonths(3), now()->subDays(31)])->count(),
            '3-6 months' => Calf::whereBetween('date_of_birth', [now()->subMonths(6), now()->subMonths(4)])->count(),
            '6+ months' => Calf::where('date_of_birth', '<', now()->subMonths(6))->count(),
        ];

        return view('calves.statistics', compact('stats', 'ageDistribution'));
    }

    /**
     * Get calves by dam.
     */
    public function byDam(Animal $dam)
    {
        $calves = Calf::where('dam_id', $dam->id)
            ->with(['sire', 'recordedBy'])
            ->latest('date_of_birth')
            ->paginate(20);

        return view('calves.by-dam', compact('calves', 'dam'));
    }
}