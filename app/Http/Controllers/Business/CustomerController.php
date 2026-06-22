<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::where('business_id', Auth::id())
                        ->where('role', 'customer')
                        ->latest()
                        ->paginate(10);

        return view('business.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.customers.create');
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
                'role' => 'customer',
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

        return redirect()->route('business.customers.index')->with('status', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Ensure user belongs to this business and is a customer
        if ($user->business_id !== Auth::id() || $user->role !== 'customer') {
            abort(403);
        }

        return view('business.customers.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Ensure user belongs to this business and is a customer
        if ($user->business_id !== Auth::id() || $user->role !== 'customer') {
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

        return redirect()->route('business.customers.index')->with('status', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Ensure user belongs to this business and is a customer
        if ($user->business_id !== Auth::id() || $user->role !== 'customer') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('business.customers.index')->with('status', 'Customer deleted successfully.');
    }
}

