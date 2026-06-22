<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    /**
     * Display a listing of claims for the business.
     */
    public function index()
    {
        $user = auth()->user();
        $claims = $user->businessClaims()->with('user')->latest()->paginate(10);

        // Calculate Metrics
        $metrics = [
            'total_claims' => $user->businessClaims()->count(),
            'total_amount' => $user->businessClaims()->sum('amount'),
            'total_points' => $user->businessClaims()->sum('rewarded_points'),
        ];

        return view('business.claims.index', compact('claims', 'metrics'));
    }

    /**
     * Display a listing of claims for the business filtered by category.
     */
    public function categoryClaims(\App\Models\ClaimCategory $category)
    {
        $user = auth()->user();
        
        // ensure category belongs to business
        if ($category->business_id !== $user->id) {
            abort(403);
        }

        $claims = $user->businessClaims()
            ->where('category_id', $category->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        // Calculate Metrics for this category
        $metrics = [
            'total_claims' => $user->businessClaims()->where('category_id', $category->id)->count(),
            'total_amount' => $user->businessClaims()->where('category_id', $category->id)->sum('amount'),
            'total_points' => $user->businessClaims()->where('category_id', $category->id)->sum('rewarded_points'),
        ];

        return view('business.claims.index', compact('claims', 'metrics', 'category'));
    }

    /**
     * Display the specified claim.
     */
    public function show(Claim $claim)
    {
        if ($claim->business_id !== auth()->id()) {
            abort(403);
        }

        return view('business.claims.show', compact('claim'));
    }

    /**
     * Update the claim status (approve/reject).
     */
    public function update(Request $request, Claim $claim)
    {
        if ($claim->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'reward_points' => ['nullable', 'integer', 'min:0'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $business = auth()->user();
            $rewardPoints = (int) $request->reward_points;

            if ($request->status === 'approved' && $rewardPoints > 0) {
                if ($business->points < $rewardPoints) {
                    throw new \Exception("Insufficient point balance to reward {$rewardPoints} points.");
                }

                // Transfer points
                $business->decrement('points', $rewardPoints);
                $claim->user->increment('points', $rewardPoints);
            } else {
                $rewardPoints = 0; // Ensure it's 0 if rejected or no points given
            }

            $claim->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'rewarded_points' => $rewardPoints,
            ]);

            // Notify the user
            $claim->user->notify(new \App\Notifications\ClaimProcessed($claim));

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('business.claims.index')->with('status', 'Claim status updated successfully!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
