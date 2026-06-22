<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\KpiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KpiCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $business = auth()->user();
        $categories = $business->kpiCategories()->latest()->paginate(10);
        
        $metrics = [
            'total_categories' => $business->kpiCategories()->count(),
            'total_active' => $business->kpiCategories()->where('is_active', true)->count(),
            'total_kpis' => \App\Models\Kpi::whereIn('category_id', $business->kpiCategories()->pluck('id'))->count(),
            'total_points_awarded' => \App\Models\Kpi::whereIn('category_id', $business->kpiCategories()->pluck('id'))->sum('rewarded_points'),
        ];

        return view('business.kpis.categories.index', compact('categories', 'metrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.kpis.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'points_reward' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $request->only(['name', 'description', 'points_reward', 'start_date', 'end_date']);
        $data['business_id'] = auth()->id();
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('kpis', 'public');
        }

        $category = KpiCategory::create($data);

        // Notify all users of this business about the new KPI goal
        $business = auth()->user();
        $usersToNotify = $business->employees->concat($business->customers);
        
        foreach ($usersToNotify as $user) {
            $user->notify(new \App\Notifications\NewKpiCategoryCreated($category));
        }

        return redirect()->route('business.kpis.categories.index')->with('success', 'KPI category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KpiCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        $kpis = $category->kpis()->with('user')->latest()->paginate(20);

        return view('business.kpis.categories.show', compact('category', 'kpis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KpiCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }
        return view('business.kpis.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KpiCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'points_reward' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'points_reward', 'start_date', 'end_date', 'is_active']);

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('kpis', 'public');
        }

        $category->update($data);

        return redirect()->route('business.kpis.categories.index')->with('success', 'KPI category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KpiCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('business.kpis.categories.index')->with('success', 'KPI category deleted successfully.');
    }
}
