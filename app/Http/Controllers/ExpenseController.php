<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['user', 'supplier'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $expenses = $query->paginate(20);
        
        // Get summary statistics (only for admin)
        $summary = [];
        if (Auth::user()->canViewExpenseTotals()) {
            $summary = [
                'total' => Expense::approved()->when($request->filled('start_date'), function($q) use ($request) {
                    $q->whereDate('date', '>=', $request->start_date);
                })->when($request->filled('end_date'), function($q) use ($request) {
                    $q->whereDate('date', '<=', $request->end_date);
                })->sum('amount'),
                'monthly_total' => Expense::approved()->forMonth()->sum('amount'),
                'pending_count' => Expense::pending()->count(),
                'by_category' => Expense::approved()
                    ->selectRaw('category, SUM(amount) as total')
                    ->groupBy('category')
                    ->get()
            ];
        }

        $categories = [
            'Animal Feed' => 'Animal Feed',
            'Veterinary' => 'Veterinary Services',
            'Labor' => 'Labor & Salaries',
            'Equipment' => 'Equipment & Maintenance',
            'Utilities' => 'Utilities',
            'Transport' => 'Transport & Fuel',
            'Supplies' => 'Supplies',
            'Medication' => 'Medication',
            'Insurance' => 'Insurance',
            'Taxes' => 'Taxes & Fees',
            'Other' => 'Other Expenses'
        ];

        return view('expenses.index', compact('expenses', 'categories', 'summary'));
    }

    public function create()
    {
        if (!Auth::user()->canManageExpenses()) {
            abort(403, 'Unauthorized action.');
        }

        $suppliers = Supplier::active()->get();
        $categories = [
            'Animal Feed' => 'Animal Feed',
            'Veterinary' => 'Veterinary Services',
            'Labor' => 'Labor & Salaries',
            'Equipment' => 'Equipment & Maintenance',
            'Utilities' => 'Utilities',
            'Transport' => 'Transport & Fuel',
            'Supplies' => 'Supplies',
            'Medication' => 'Medication',
            'Insurance' => 'Insurance',
            'Taxes' => 'Taxes & Fees',
            'Other' => 'Other Expenses'
        ];

        $paymentMethods = [
            'cash' => 'Cash',
            'mpesa' => 'M-Pesa',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'other' => 'Other'
        ];

        return view('expenses.create', compact('suppliers', 'categories', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->canManageExpenses()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Set status based on user role
        $status = Auth::user()->isAdmin() ? 'approved' : 'pending';

        $expense = Expense::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'status' => $status
        ]));

        return redirect()->route('expenses.index')
            ->with('success', $status === 'approved' 
                ? 'Expense recorded successfully!' 
                : 'Expense submitted for approval!');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if (!Auth::user()->canManageExpenses()) {
            abort(403, 'Unauthorized action.');
        }

        // Only admin can edit approved expenses
        if ($expense->is_approved && !Auth::user()->isAdmin()) {
            abort(403, 'Cannot edit approved expenses.');
        }

        $suppliers = Supplier::active()->get();
        $categories = [
            'Animal Feed' => 'Animal Feed',
            'Veterinary' => 'Veterinary Services',
            'Labor' => 'Labor & Salaries',
            'Equipment' => 'Equipment & Maintenance',
            'Utilities' => 'Utilities',
            'Transport' => 'Transport & Fuel',
            'Supplies' => 'Supplies',
            'Medication' => 'Medication',
            'Insurance' => 'Insurance',
            'Taxes' => 'Taxes & Fees',
            'Other' => 'Other Expenses'
        ];

        $paymentMethods = [
            'cash' => 'Cash',
            'mpesa' => 'M-Pesa',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'other' => 'Other'
        ];

        return view('expenses.edit', compact('expense', 'suppliers', 'categories', 'paymentMethods'));
    }

    public function update(Request $request, Expense $expense)
    {
        if (!Auth::user()->canManageExpenses()) {
            abort(403, 'Unauthorized action.');
        }

        // Only admin can edit approved expenses
        if ($expense->is_approved && !Auth::user()->isAdmin()) {
            abort(403, 'Cannot edit approved expenses.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can delete expenses.');
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }

    public function approve(Expense $expense)
    {
        if (!Auth::user()->canApproveExpenses()) {
            abort(403, 'Unauthorized action.');
        }

        $expense->update(['status' => 'approved']);

        return back()->with('success', 'Expense approved successfully!');
    }

    public function reject(Expense $expense)
    {
        if (!Auth::user()->canApproveExpenses()) {
            abort(403, 'Unauthorized action.');
        }

        $expense->update(['status' => 'rejected']);

        return back()->with('success', 'Expense rejected!');
    }

    public function dashboardSummary()
    {
        if (!Auth::user()->canViewExpenseTotals()) {
            return [];
        }

        $today = now()->toDateString();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        return [
            'today' => Expense::approved()->forDate($today)->sum('amount'),
            'month_to_date' => Expense::approved()
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount'),
            'pending_count' => Expense::pending()->count(),
            'top_categories' => Expense::approved()
                ->forMonth()
                ->selectRaw('category, SUM(amount) as total')
                ->groupBy('category')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    public function monthlyReport(Request $request)
    {
        if (!Auth::user()->canViewExpenseTotals()) {
            abort(403, 'Unauthorized action.');
        }

        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $expenses = Expense::approved()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['user', 'supplier'])
            ->orderBy('date')
            ->get();

        $summary = [
            'total' => $expenses->sum('amount'),
            'by_category' => $expenses->groupBy('category')->map->sum('amount'),
            'by_day' => $expenses->groupBy(function($item) {
                return $item->date->format('Y-m-d');
            })->map->sum('amount')
        ];

        return view('expenses.monthly-report', compact('expenses', 'summary', 'year', 'month'));
    }
}