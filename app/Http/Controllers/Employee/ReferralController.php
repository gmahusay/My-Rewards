<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralCategory;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businessId = $user->business_id;
        
        $categories = ReferralCategory::where('business_id', $businessId)
            ->where('is_active', true)
            ->latest()
            ->get();
            
        return view('customer.referrals.index', compact('categories')); // Reusing Customer View
    }

    public function show(ReferralCategory $category)
    {
        $user = auth()->user();
        if ($category->business_id !== $user->business_id) {
            abort(403);
        }

        $myReferrals = $user->madeReferrals()
            ->where('category_id', $category->id)
            ->latest()
            ->get();

        return view('customer.referrals.show', compact('category', 'myReferrals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:referral_categories,id',
            'referred_email' => 'required|email',
            'notes' => 'nullable|string',
        ]);

        if ($request->referred_email === auth()->user()->email) {
            return back()->with('error', 'You cannot refer yourself.');
        }

        $exists = Referral::where('category_id', $request->category_id)
            ->where('referred_email', $request->referred_email)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This email has already been referred for this campaign.');
        }

        $referral = Referral::create([
            'category_id' => $request->category_id,
            'referrer_id' => auth()->id(),
            'referred_email' => $request->referred_email,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Notify Business Owner
        $business = $referral->category->business; // User model relationship
        $business->notify(new \App\Notifications\NewReferralSubmitted($referral));

        return back()->with('success', 'Referral submitted successfully!');
    }
}
