<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralCategory;
use App\Models\ReferralVisit;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        $categories = ReferralCategory::where('business_id', $businessId)
            ->where('is_active', true)
            ->withCount('participants')
            ->with(['participants' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->get();

        // Annotate each category with whether the user has joined
        $categories->each(function ($category) use ($user) {
            $category->has_joined = $category->participants->isNotEmpty();
        });

        return view('customer.referrals.index', compact('categories'));
    }

    public function show(ReferralCategory $category)
    {
        $user = auth()->user();
        // Ensure the category belongs to the user's business
        if ($category->business_id !== $user->business_id) {
            abort(403);
        }

        $hasJoined = $category->hasJoinedBy($user);

        // Link clicks for this user on this category
        $myVisits = \App\Models\ReferralVisit::where('referrer_id', $user->id)
            ->where('category_id', $category->id)
            ->latest()
            ->get();

        return view('customer.referrals.show', compact('category', 'myVisits', 'hasJoined'));
    }

    public function join(Request $request, ReferralCategory $category)
    {
        $user = auth()->user();

        if ($category->business_id !== $user->business_id) {
            abort(403);
        }

        if (!$category->is_active) {
            return back()->with('error', 'This referral campaign is no longer active.');
        }

        // Already joined?
        if ($category->hasJoinedBy($user)) {
            return back()->with('error', 'You have already joined this campaign.');
        }

        $category->participants()->attach($user->id);

        return redirect()
            ->route('customer.referrals.show', $category)
            ->with('status', 'You have joined the campaign! Your referral link is now active.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:referral_categories,id',
            'referred_email' => 'required|email',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user();
        $category = ReferralCategory::findOrFail($request->category_id);

        // Must have joined before submitting a referral
        if (!$category->hasJoinedBy($user)) {
            return back()->with('error', 'You must join this referral campaign before submitting referrals.');
        }

        // Prevent self-referral
        if ($request->referred_email === $user->email) {
            return back()->with('error', 'You cannot refer yourself.');
        }

        // Check for duplicates
        $exists = Referral::where('category_id', $request->category_id)
            ->where('referred_email', $request->referred_email)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This email has already been referred for this campaign.');
        }

        $referral = Referral::create([
            'category_id' => $request->category_id,
            'referrer_id' => $user->id,
            'referred_email' => $request->referred_email,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Notify Business Owner
        $business = $referral->category->business;
        $business->notify(new \App\Notifications\NewReferralSubmitted($referral));

        return back()->with('success', 'Referral submitted successfully!');
    }
}

