<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\ClaimCategory;
use Illuminate\Http\Request;

class ClaimCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $categories = $user->claimCategories()->latest()->paginate(10);

        // Metrics
        $metrics = [
            'total_categories' => $user->claimCategories()->count(),
            'total_active' => $user->claimCategories()->where('is_active', true)->count(),
            'total_points_rewarded' => \App\Models\Claim::whereIn('category_id', $user->claimCategories()->pluck('id'))
                ->sum('rewarded_points'),
        ];

        return view('business.claims.categories.index', compact('categories', 'metrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.claims.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'points_reward' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'description' => ['nullable', 'string'],
            'end_date' => ['nullable', 'date', 'after:today'],
        ]);

        $data = [
            'name' => $request->name,
            'points_reward' => $request->points_reward,
            'description' => $request->description,
            'is_active' => true,
            'end_date' => $request->end_date,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        $category = auth()->user()->claimCategories()->create($data);

        // Notify associated users
        $business = auth()->user();
        $usersToNotify = $business->employees->concat($business->customers);
        
        foreach ($usersToNotify as $user) {
            $user->notify(new \App\Notifications\NewClaimCategoryCreated($category));
        }

        return redirect()->route('business.claims.categories.index')->with('status', 'Category created successfully and users notified!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClaimCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        return view('business.claims.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClaimCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'points_reward' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'description' => ['nullable', 'string'],
            'end_date' => ['nullable', 'date'], // Can be any date for update
            'is_active' => ['required', 'boolean'],
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('business.claims.categories.index')->with('status', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClaimCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        if ($category->claims()->exists()) {
            return back()->with('error', 'Cannot delete category that has existing claims.');
        }

        $category->delete();

        return redirect()->route('business.claims.categories.index')->with('status', 'Category deleted successfully!');
    }
}
