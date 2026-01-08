<?php

namespace App\Http\Controllers;

use App\Models\MilkSupply;
use App\Models\Supplier;
use App\Models\MilkProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MilkSupplyController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:recordMilkSupply')->only(['create', 'store', 'edit', 'update']);
        $this->middleware('can:approvePayments')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = MilkSupply::with(['supplier', 'recorder', 'approver']);
        
        // Filters
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $milkSupplies = $query->latest()->paginate(20);
        $suppliers = Supplier::active()->get();
        
        // Statistics
        $todayTotal = MilkSupply::whereDate('date', today())->sum('quantity_liters');
        $todayWaste = MilkSupply::whereDate('date', today())->sum('waste_liters');
        $monthTotal = MilkSupply::whereMonth('date', now()->month)
                                ->whereYear('date', now()->year)
                                ->sum('quantity_liters');
        
        return view('milk-supplies.index', compact('milkSupplies', 'suppliers', 'todayTotal', 'todayWaste', 'monthTotal'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->get();
        $totalMilk = MilkProduction::whereDate('date', today())
            ->sum(DB::raw('COALESCE(morning_yield, 0) + COALESCE(afternoon_yield, 0) + COALESCE(evening_yield, 0)'));
        $monthTotal = MilkProduction::whereMonth('date', now()->month)
    ->whereYear('date', now()->year)
    ->sum(DB::raw('COALESCE(morning_yield, 0) + COALESCE(afternoon_yield, 0) + COALESCE(evening_yield, 0)'));
        $todaySupplied = MilkSupply::whereDate('date', today())->sum('quantity_liters');
        $todayMilk = $totalMilk;
        $availableMilk = $todayMilk - $todaySupplied;
        
        return view('milk-supplies.create', compact('suppliers', 'availableMilk'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'quantity_liters' => 'required|numeric|min:0.01',
            'rate_per_liter' => 'required|numeric|min:0',
            'waste_liters' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
    
        // Check available milk
        $totalMilk = MilkProduction::whereDate('date', $validated['date'])
            ->sum(DB::raw('COALESCE(morning_yield, 0) + COALESCE(afternoon_yield, 0) + COALESCE(evening_yield, 0)'));
        
        $totalSupplied = MilkSupply::whereDate('date', $validated['date'])->sum('quantity_liters');
        $available = $totalMilk - $totalSupplied;
        
        if ($validated['quantity_liters'] > $available) {
            return redirect()->back()
                ->with('error', "Insufficient milk available. Available: {$available} L")
                ->withInput();
        }
    
        $validated['recorded_by'] = auth()->id();
        $validated['total_amount'] = $validated['quantity_liters'] * $validated['rate_per_liter'];
        
        $milkSupply = MilkSupply::create($validated);
        
        return redirect()->route('milk-supplies.index')
            ->with('success', 'Milk supply recorded successfully. Payment has been auto-created and is pending approval.');
    }
    public function show(MilkSupply $milkSupply)
    {
        $milkSupply->load(['supplier', 'recorder', 'approver']);
        return view('milk-supplies.show', compact('milkSupply'));
    }

    public function edit(MilkSupply $milkSupply)
    {
        if ($milkSupply->status === 'approved') {
            return redirect()->route('milk-supplies.show', $milkSupply)
                ->with('error', 'Cannot edit approved supply record.');
        }
        
        $suppliers = Supplier::active()->get();
        return view('milk-supplies.edit', compact('milkSupply', 'suppliers'));
    }

    public function update(Request $request, MilkSupply $milkSupply)
    {
        if ($milkSupply->status === 'approved') {
            return redirect()->back()
                ->with('error', 'Cannot update approved supply record.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'quantity_liters' => 'required|numeric|min:0.01',
            'rate_per_liter' => 'required|numeric|min:0',
            'waste_liters' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['total_amount'] = $validated['quantity_liters'] * $validated['rate_per_liter'];
        $milkSupply->update($validated);
        
        return redirect()->route('milk-supplies.index')
            ->with('success', 'Milk supply updated successfully.');
    }

    public function destroy(MilkSupply $milkSupply)
    {
        if ($milkSupply->status === 'approved') {
            return redirect()->back()
                ->with('error', 'Cannot delete approved supply record.');
        }
        
        $milkSupply->delete();
        
        return redirect()->route('milk-supplies.index')
            ->with('success', 'Milk supply deleted successfully.');
    }

    public function approve(MilkSupply $milkSupply)
    {
        if (!auth()->user()->canApprovePayments()) {
            abort(403);
        }

        $milkSupply->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Milk supply approved successfully.');
    }

    public function dailyReport()
    {
        $date = request('date', today()->format('Y-m-d'));
        $supplies = MilkSupply::whereDate('date', $date)
            ->with('supplier')
            ->get();
        
        $totalLiters = $supplies->sum('quantity_liters');
        $totalWaste = $supplies->sum('waste_liters');
        $totalAmount = $supplies->sum('total_amount');
        
        return view('milk-supplies.daily-report', compact('supplies', 'date', 'totalLiters', 'totalWaste', 'totalAmount'));
    }
}