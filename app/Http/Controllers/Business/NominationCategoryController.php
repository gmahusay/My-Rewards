<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\NominationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NominationCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $categories = $user->nominationCategories()->latest()->paginate(10);

        // Calculate Metrics
        $metrics = [
            'total_active' => $user->nominationCategories()
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'total_awarded' => $user->nominationCategories()->whereNotNull('winner_id')->count(),
            'total_points_awarded' => $user->nominationCategories()->whereNotNull('winner_id')->sum('points_reward'),
        ];

        return view('business.nominations.categories.index', compact('categories', 'metrics'));
    }

    public function create()
    {
        return view('business.nominations.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'points_reward' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $data = $request->except('image');
        $data['business_id'] = auth()->id();
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('nominations', 'public');
        }

        $category = NominationCategory::create($data);

        // Notify all employees of this business
        $business = auth()->user();
        \Illuminate\Support\Facades\Notification::send(
            $business->employees, 
            new \App\Notifications\NewNominationCategory($category)
        );

        return redirect()->route('business.nominations.categories.index')->with('status', 'Nomination category created successfully!');
    }

    public function edit(NominationCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }
        return view('business.nominations.categories.edit', compact('category'));
    }

    public function update(Request $request, NominationCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'points_reward' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('nominations', 'public');
        }

        $category->update($data);

        return redirect()->route('business.nominations.categories.index')->with('status', 'Nomination category updated successfully!');
    }

    public function destroy(NominationCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('business.nominations.categories.index')->with('status', 'Nomination category deleted successfully!');
    }

    public function results(NominationCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        $nominations = \App\Models\Nomination::where('category_id', $category->id)
            ->with(['nominee', 'nominator'])
            ->get();

        $results = $nominations->groupBy('nominee_id')->sortByDesc(function ($group) {
            return $group->count();
        });

        return view('business.nominations.categories.results', compact('category', 'results'));
    }

    public function award(Request $request, NominationCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        if ($category->winner_id) {
            return back()->with('error', 'A winner has already been awarded for this category.');
        }

        $request->validate([
            'winner_id' => ['required', 'exists:users,id'],
        ]);

        $business = auth()->user();
        $points = $category->points_reward;

        if ($business->points < $points) {
            return back()->with('error', 'Insufficient points balance to award this prize.');
        }

        $winner = \App\Models\User::findOrFail($request->winner_id);

        try {
            \DB::beginTransaction();

            // Deduct from business
            $business->decrement('points', $points);
            
            // Add to winner
            $winner->increment('points', $points);

            // Record the win
            $category->update([
                'winner_id' => $winner->id,
                'awarded_at' => now(),
            ]);

            // Notify the winner
            $winner->notify(new \App\Notifications\NominationAwarded($category));

            // Update Gamification Progress for Nomination
            app(\App\Services\Gamification\GamificationService::class)->updateCampaignProgress(
                $winner->id,
                'nomination',
                1
            );

            \DB::commit();

            return redirect()->route('business.nominations.categories.index')->with('status', "Congratulations! {$winner->name} has been awarded {$points} points.");
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to award points. Please try again.');
        }
    }
}
