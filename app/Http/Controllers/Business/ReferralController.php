<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $business = auth()->user();
        
        // Get all referrals belonging to categories created by this business
        $referrals = Referral::whereHas('category', function ($query) use ($business) {
            $query->where('business_id', $business->id);
        })
        ->with(['category', 'referrer'])
        ->latest()
        ->paginate(15);

        return view('business.referrals.index', compact('referrals'));
    }

    /**
     * Approve the referral and award points.
     */
    public function approve(Referral $referral)
    {
        $business = auth()->user();

        // Authorization: Ensure the referral belongs to this business
        if ($referral->category->business_id !== $business->id) {
            abort(403);
        }

        if ($referral->status !== 'pending') {
            return back()->with('error', 'This referral has already been processed.');
        }

        $pointsToAward = $referral->category->points_reward;
        $referrer = $referral->referrer;

        if ($business->points < $pointsToAward) {
            return back()->with('error', 'Insufficient points balance to approve this referral.');
        }

        try {
            DB::beginTransaction();

            // Deduct from Business
            $business->decrement('points', $pointsToAward);
            
            // Add to Referrer
            $referrer->increment('points', $pointsToAward);

            // Record Transactions
            // 1. Transaction from Business perspective (Out)
            \App\Models\PointTransaction::create([
                'sender_id' => $business->id,
                'receiver_id' => $referrer->id,
                'amount' => $pointsToAward,
                'description' => "Referral Reward: {$referral->category->name}",
            ]);

            // Update Referral Status
            $referral->update([
                'status' => 'approved',
                'rewarded_points' => $pointsToAward,
            ]);

            // Notify Referrer
            $referrer->notify(new \App\Notifications\ReferralApproved($referral));

            // Update Gamification Progress for Referral
            app(\App\Services\Gamification\GamificationService::class)->updateCampaignProgress(
                $referrer->id,
                'referral',
                1
            );

            DB::commit();

            return back()->with('success', 'Referral approved and points awarded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process referral. Please try again.');
        }
    }

    /**
     * Reject the referral.
     */
    public function reject(Referral $referral)
    {
        $business = auth()->user();

        if ($referral->category->business_id !== $business->id) {
            abort(403);
        }

        if ($referral->status !== 'pending') {
            return back()->with('error', 'This referral has already been processed.');
        }

        $referral->update(['status' => 'rejected']);

        // Notify Referrer
        $referral->referrer->notify(new \App\Notifications\ReferralRejected($referral));

        return back()->with('success', 'Referral has been rejected.');
    }
}
