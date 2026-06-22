<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\ReferralCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class ReferralCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $business = auth()->user();
        $categories = $business->referralCategories()->latest()->paginate(10);
        
        $metrics = [
             'total_active' => $business->referralCategories()->where('is_active', true)->count(),
             'total_referrals' => $business->madeReferrals()->count(), // Wait, Business doesn't make referrals, used wrong relation?
             // Ah, Business *receives* referrals through categories.
             // Relation: $business->referralCategories->referrals? 
             // Ideally we need a 'receivedReferrals' relation on User or query via categories.
             'total_points_awarded' => \App\Models\Referral::whereIn('category_id', $business->referralCategories()->pluck('id'))->sum('rewarded_points'),
        ];
        
        // Fix metric: Total referrals received
        $metrics['total_referrals'] = \App\Models\Referral::whereIn('category_id', $business->referralCategories()->pluck('id'))->count();

        return view('business.referrals.categories.index', compact('categories', 'metrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.referrals.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_reward' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'points_reward']);
        $data['business_id'] = auth()->id();
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('referrals', 'public');
        }

        $category = ReferralCategory::create($data);

    // Generate encrypted referral link token (category slug/name and will be combined with user token on share)
    $token = Crypt::encryptString($category->name);
    $category->referral_link = $token;
    $category->save();

        // Notify all users of this business about the new campaign
        $users = \App\Models\User::where('business_id', $category->business_id)->get();
        \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\NewReferralCampaign($category));

        return redirect()->route('business.referrals.categories.index')->with('success', 'Referral category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReferralCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }
        return view('business.referrals.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReferralCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_reward' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'points_reward', 'is_active']);

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('referrals', 'public');
        }

        $category->update($data);

    // Re-generate encrypted token if name changed
    $token = Crypt::encryptString($category->name);
    $category->referral_link = $token;
    $category->save();

        return redirect()->route('business.referrals.categories.index')->with('success', 'Referral category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReferralCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('business.referrals.categories.index')->with('success', 'Referral category deleted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReferralCategory $category)
    {
        if ($category->business_id !== auth()->id()) {
            abort(403);
        }

        $participants = $category->participants()->orderBy('name')->paginate(20);

        // For each participant, fetch visit stats per participant
        foreach ($participants as $participant) {
            $participant->visits = \App\Models\ReferralVisit::where('referrer_id', $participant->id)
                ->where('category_id', $category->id)
                ->latest()
                ->get();
        }

        return view('business.referrals.categories.show', compact('category', 'participants'));
    }
}
