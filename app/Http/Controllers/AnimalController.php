<?php
namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index()
    {
        $animals = Animal::with(['dam', 'sire'])->latest()->paginate(20);
        return view('animals.index', compact('animals'));
    }

    public function create()
    {
        $animals = Animal::active()->get();
        return view('animals.create', compact('animals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'animal_id' => 'required|unique:animals',
            'ear_tag' => 'required|unique:animals',
            'name' => 'nullable|string|max:100',
            'breed' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'source' => 'required|in:born,purchased',
            'status' => 'required|in:calf,heifer,lactating,dry,sold,dead',
            'date_added' => 'required|date',
        ]);

        Animal::create($validated);

        return redirect()->route('animals.index')
            ->with('success', 'Animal registered successfully!');
    }

    public function show(Animal $animal)
    {
        $animal->load(['breedingRecords', 'milkProductions', 'healthRecords']);
        return view('animals.show', compact('animal'));
    }

    public function edit(Animal $animal)
    {
        // Get all active animals for parent selection (excluding the current one)
        $animals = Animal::active()->where('id', '!=', $animal->id)->get();
        
        return view('animals.edit', compact('animal', 'animals'));
    }

    public function update(Request $request, Animal $animal)
    {
        $validated = $request->validate([
            'animal_id' => 'required|unique:animals,animal_id,' . $animal->id,
            'ear_tag' => 'required|unique:animals,ear_tag,' . $animal->id,
            'name' => 'nullable|string|max:100',
            'breed' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'source' => 'required|in:born,purchased',
            'status' => 'required|in:calf,heifer,lactating,dry,sold,dead',
            'date_added' => 'required|date',
            'dam_id' => 'nullable|exists:animals,id',
            'sire_id' => 'nullable|exists:animals,id',
            'is_active' => 'boolean',
            'date_sold' => 'nullable|date|required_if:status,sold',
            'date_died' => 'nullable|date|required_if:status,dead',
            'sale_price' => 'nullable|numeric|min:0',
            'death_cause' => 'nullable|string|max:255|required_if:status,dead',
        ]);

        // Handle file upload if exists
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $path = $request->file('photo')->store('animal-photos', 'public');
            $validated['photo'] = $path;
        }

        // Update the animal record
        $animal->update($validated);

        return redirect()->route('animals.show', $animal)
            ->with('success', 'Animal updated successfully!');
    }

    public function destroy(Animal $animal)
    {
        // Check if animal has related records before deleting
        if ($animal->milkProductions()->exists() || 
            $animal->breedingRecords()->exists() || 
            $animal->healthRecords()->exists()) {
            
            return redirect()->back()
                ->with('error', 'Cannot delete animal with related records. Consider marking as sold or dead instead.');
        }

        $animal->delete();

        return redirect()->route('animals.index')
            ->with('success', 'Animal deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $animals = Animal::where('animal_id', 'LIKE', "%{$query}%")
            ->orWhere('ear_tag', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->paginate(20);

        return view('animals.search', compact('animals', 'query'));
    }
}