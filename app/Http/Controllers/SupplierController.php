<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\MilkSupply;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with(['creator', 'milkSupplies' => function($q) {
            $q->latest()->limit(5);
        }])->latest()->paginate(20);
        
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'rate_per_liter' => 'required|numeric|min:0',
            'payment_frequency' => 'required|in:daily,weekly,monthly',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        
        Supplier::create($validated);
        
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['creator', 'milkSupplies' => function($q) {
            $q->latest()->paginate(10);
        }, 'payments' => function($q) {
            $q->latest()->paginate(10);
        }]);
        
        $stats = [
            'total_supplied' => $supplier->total_supplied,
            'total_paid' => $supplier->payments()->where('status', 'approved')->sum('amount'),
            'balance_due' => $supplier->balance,
            'current_rate' => $supplier->rate_per_liter,
        ];
        
        return view('suppliers.show', compact('supplier', 'stats'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'rate_per_liter' => 'required|numeric|min:0',
            'payment_frequency' => 'required|in:daily,weekly,monthly',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($validated);
        
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        // Check if supplier has any milk supplies
        if ($supplier->milkSupplies()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete supplier with milk supply records.');
        }
        
        $supplier->delete();
        
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}