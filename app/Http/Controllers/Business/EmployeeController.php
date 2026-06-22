<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::where('business_id', Auth::id())
                        ->where('role', 'employee')
                        ->latest()
                        ->paginate(10);

        return view('business.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // 1MB Max
            'points' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->points && Auth::user()->points < $request->points) {
            return back()->withInput()->withErrors(['points' => 'Insufficient points balance.']);
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee',
                'business_id' => Auth::id(),
            ]);

            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->update(['profile_photo_path' => $path]);
            }

            if ($request->points && $request->points > 0) {
                $business = Auth::user();
                $business->deductPoints($request->points);
                $user->addPoints($request->points, "Initial Allocation from Business", $business);
            }
        });

        return redirect()->route('business.employees.index')->with('status', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ensure user belongs to this business and is an employee
        if ($user->business_id !== Auth::id() || $user->role !== 'employee') {
            abort(403);
        }

        return view('business.employees.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ensure user belongs to this business and is an employee
        if ($user->business_id !== Auth::id() || $user->role !== 'employee') {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);
        }

        return redirect()->route('business.employees.index')->with('status', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Ensure user belongs to this business and is an employee
        if ($user->business_id !== Auth::id() || $user->role !== 'employee') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('business.employees.index')->with('status', 'Employee deleted successfully.');
    }
}

