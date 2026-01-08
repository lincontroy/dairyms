<?php

namespace App\Http\Controllers;

use App\Models\SupplierPayment;
use App\Models\Supplier;
use App\Models\MilkSupply;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('can:approvePayments')->except(['index', 'show', 'create', 'store']);
    }

    public function index(Request $request)
    {
        $query = SupplierPayment::with(['supplier', 'creator', 'approver']);
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        
        $payments = $query->latest()->paginate(20);
        $suppliers = Supplier::active()->get();
        
        // Statistics
        $pendingAmount = SupplierPayment::where('status', 'pending')->sum('amount');
        $approvedAmount = SupplierPayment::where('status', 'approved')->sum('amount');
        $totalSuppliers = Supplier::active()->count();
        
        return view('payments.index', compact('payments', 'suppliers', 'pendingAmount', 'approvedAmount', 'totalSuppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->get();
        
        // Get suppliers with pending payments
        $suppliersWithBalance = Supplier::whereHas('milkSupplies', function($q) {
            $q->where('status', 'approved');
        })->with(['milkSupplies' => function($q) {
            $q->whereDoesntHave('payment')
              ->where('status', 'approved');
        }])->get();
        
        // Get unpaid milk supplies for the first supplier (for dropdown)
        $firstSupplier = $suppliers->first();
        $unpaidSupplies = [];
        if ($firstSupplier) {
            $unpaidSupplies = $firstSupplier->milkSupplies()
                ->whereDoesntHave('payment')
                ->where('status', 'approved')
                ->get();
        }
        
        return view('payments.create', compact('suppliers', 'suppliersWithBalance', 'unpaidSupplies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'milk_supply_id' => 'nullable|exists:milk_supplies,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,cheque',
            'reference_number' => 'nullable|string|max:50',
            'payment_period_start' => 'required|date',
            'payment_period_end' => 'required|date|after:payment_period_start',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        
        // If no specific milk supply, check if amount exceeds balance
        if (!$validated['milk_supply_id']) {
            $supplier = Supplier::find($validated['supplier_id']);
            $dueAmount = $supplier->balance;
            
            if ($validated['amount'] > $dueAmount) {
                return redirect()->back()
                    ->with('error', "Payment amount exceeds due amount. Due: KSh " . number_format($dueAmount, 2))
                    ->withInput();
            }
        }
        
        SupplierPayment::create($validated);
        
        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(SupplierPayment $payment)
    {
        $payment->load(['supplier', 'creator', 'approver', 'milkSupply']);
        return view('payments.show', compact('payment'));
    }

    public function approve(SupplierPayment $payment)
    {
        $payment->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Payment approved successfully.');
    }

    public function reject(SupplierPayment $payment)
    {
        $payment->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        return redirect()->back()
            ->with('success', 'Payment rejected successfully.');
    }

    public function bulkApprove(Request $request)
    {
        $paymentIds = $request->input('payment_ids', []);
        
        if (empty($paymentIds)) {
            return redirect()->back()
                ->with('error', 'No payments selected.');
        }
        
        SupplierPayment::whereIn('id', $paymentIds)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        
        return redirect()->back()
            ->with('success', count($paymentIds) . ' payments approved successfully.');
    }

    public function generatePayments()
    {
        $suppliers = Supplier::active()->get();
        $generatedPayments = [];
        
        foreach ($suppliers as $supplier) {
            $dueAmount = $supplier->balance;
            
            if ($dueAmount > 0) {
                // Create payment record
                $payment = SupplierPayment::create([
                    'supplier_id' => $supplier->id,
                    'payment_date' => now(),
                    'amount' => $dueAmount,
                    'payment_method' => 'bank_transfer',
                    'payment_period_start' => now()->subMonth()->startOfMonth(),
                    'payment_period_end' => now()->subMonth()->endOfMonth(),
                    'notes' => 'Auto-generated monthly payment',
                    'created_by' => auth()->id(),
                ]);
                
                $generatedPayments[] = $payment;
            }
        }
        
        return redirect()->route('payments.index')
            ->with('success', count($generatedPayments) . ' payments generated successfully.');
    }

    // Get unpaid supplies for a supplier
    public function getUnpaidSupplies($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $unpaidSupplies = $supplier->milkSupplies()
            ->whereDoesntHave('payment')
            ->where('status', 'approved')
            ->get();
            
        return view('payments.partials.unpaid-supplies', compact('unpaidSupplies'));
    }
}