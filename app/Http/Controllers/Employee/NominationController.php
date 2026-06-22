<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Nomination;
use App\Models\NominationCategory;
use App\Models\User;
use Illuminate\Http\Request;

class NominationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $categories = NominationCategory::where('business_id', $user->business_id)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->withCount(['nominations' => function ($query) use ($user) {
                $query->where('nominator_id', $user->id);
            }])
            ->latest()
            ->paginate(10);

        return view('employee.nominations.index', compact('categories'));
    }

    public function create(NominationCategory $category)
    {
        $user = auth()->user();
        
        if ($category->business_id !== $user->business_id || !$category->isActive()) {
            abort(403);
        }

        // Check if already nominated in this category
        $existing = Nomination::where('category_id', $category->id)
            ->where('nominator_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('employee.nominations.index')->with('error', 'You have already submitted a nomination for this category.');
        }

        // Get other employees of the same business
        $employees = User::where('role', 'employee')
            ->where('business_id', $user->business_id)
            ->where('id', '!=', $user->id)
            ->get();

        return view('employee.nominations.create', compact('category', 'employees'));
    }

    public function store(Request $request, NominationCategory $category)
    {
        $user = auth()->user();

        if ($category->business_id !== $user->business_id || !$category->isActive()) {
            abort(403);
        }

        $request->validate([
            'nominee_id' => ['required', 'exists:users,id'],
            'reason' => ['required', 'string', 'min:10'],
        ]);

        // Check nominee belongs to the same business and is an employee
        $nominee = User::findOrFail($request->nominee_id);
        if ($nominee->business_id !== $user->business_id || !$nominee->hasRole('employee') || $nominee->id === $user->id) {
            return back()->with('error', 'Invalid nominee selected.');
        }

        // Double check uniqueness
        $existing = Nomination::where('category_id', $category->id)
            ->where('nominator_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('employee.nominations.index')->with('error', 'You have already submitted a nomination for this category.');
        }

        $nomination = Nomination::create([
            'category_id' => $category->id,
            'nominator_id' => $user->id,
            'nominee_id' => $request->nominee_id,
            'reason' => $request->reason,
        ]);

        // Notify the business owner
        $businessOwner = User::find($user->business_id);
        if ($businessOwner) {
            $nomination->load(['nominator', 'nominee', 'category']);
            $businessOwner->notify(new \App\Notifications\UserNominated($nomination));
        }

        return redirect()->route('employee.nominations.index')->with('status', 'Nomination submitted successfully!');
    }
}
