<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businesses = User::where('role', 'business')
                        ->latest()
                        ->paginate(10);

        return view('admin.businesses.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.businesses.create');
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
            'points' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'business',
            ]);

            if ($request->points && $request->points > 0) {
                // Determine sender (Admin) or system
                $sender = Auth::user(); 
                $user->addPoints($request->points, "Initial Balance assigned by Admin", $sender);
            }
        });

        return redirect()->route('admin.businesses.index')->with('status', 'Business user created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $business)
    {
        if ($business->role !== 'business') {
            abort(403);
        }
        return view('admin.businesses.edit', compact('business'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $business)
    {
        if ($business->role !== 'business') {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $business->id],
        ]);

        $business->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Note: Password update is optional and usually handled separately, but could be added here if needed.
        
        return redirect()->route('admin.businesses.index')->with('status', 'Business updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $business)
    {
         if ($business->role !== 'business') {
            abort(403);
        }
        
        $business->delete();

        return redirect()->route('admin.businesses.index')->with('status', 'Business deleted successfully.');
    }
}

