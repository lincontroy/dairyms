<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->canManageUsers()) {
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have permission to manage users.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::latest()->paginate(20);
        $roles = ['admin', 'manager', 'vet', 'staff'];
        
        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = ['manager', 'vet', 'staff']; // Admins can only be created by other admins
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,vet,staff',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Only admins can create admin users
        if ($validated['role'] === 'admin' && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Only administrators can create admin users.');
        }

        $validated['password'] = Hash::make($validated['password']);
        
        // Set default notification preferences
        $validated['email_notifications'] = true;
        $validated['sms_notifications'] = false;
        $validated['health_alerts'] = true;

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = ['manager', 'vet', 'staff'];
        if (auth()->user()->isAdmin()) {
            $roles[] = 'admin';
        }
        
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,vet,staff',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'health_alerts' => 'boolean',
        ]);

        // Role change restrictions
        if ($validated['role'] !== $user->role) {
            // Only admins can change roles to/from admin
            if (in_array('admin', [$validated['role'], $user->role]) && !auth()->user()->isAdmin()) {
                return back()->with('error', 'Only administrators can change admin roles.');
            }
            
            // Cannot change own role if not admin
            if ($user->id === auth()->id() && !auth()->user()->isAdmin()) {
                return back()->with('error', 'You cannot change your own role.');
            }
        }

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Cannot delete yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Show pending milk records for approval.
     */
    public function pendingMilkApprovals()
    {
        if (!auth()->user()->canApproveMilkRecords()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to approve milk records.');
        }

        $pendingRecords = \App\Models\MilkProduction::with(['animal', 'milker'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('users.pending-milk-approvals', compact('pendingRecords'));
    }

    /**
     * Approve a milk record.
     */
    public function approveMilkRecord(Request $request, $id)
    {
        if (!auth()->user()->canApproveMilkRecords()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $milkRecord = \App\Models\MilkProduction::findOrFail($id);
        
        $milkRecord->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('users.pending-milk-approvals')
            ->with('success', 'Milk record approved successfully!');
    }

    /**
     * Reject a milk record.
     */
    public function rejectMilkRecord(Request $request, $id)
    {
        if (!auth()->user()->canApproveMilkRecords()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $milkRecord = \App\Models\MilkProduction::findOrFail($id);
        
        $milkRecord->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $milkRecord->notes . "\n\nRejected: " . ($request->rejection_reason ?? 'No reason provided'),
        ]);

        return redirect()->route('users.pending-milk-approvals')
            ->with('success', 'Milk record rejected successfully!');
    }

    /**
     * User activity log (simple version).
     */
    public function activityLog(User $user)
    {
        if (!auth()->user()->canManageUsers()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view user activity.');
        }

        // Get recent activities (simplified - in a real app you'd use an activity log package)
        $milkRecords = $user->milkProductions()->latest()->take(10)->get();
        $healthRecords = $user->healthRecords()->latest()->take(10)->get();
        
        return view('users.activity-log', compact('user', 'milkRecords', 'healthRecords'));
    }
}