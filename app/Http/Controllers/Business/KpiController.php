<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Kpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $business = auth()->user();
        
        $kpis = Kpi::whereHas('category', function ($query) use ($business) {
            $query->where('business_id', $business->id);
        })
        ->with(['category', 'user'])
        ->latest()
        ->paginate(15);

        return view('business.kpis.index', compact('kpis'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Kpi $kpi)
    {
        $business = auth()->user();

        if ($kpi->category->business_id !== $business->id) {
            abort(403);
        }

        return view('business.kpis.show', compact('kpi'));
    }

    /**
     * Approve the KPI and award points.
     */
    public function approve(Kpi $kpi)
    {
        $business = auth()->user();

        if ($kpi->category->business_id !== $business->id) {
            abort(403);
        }

        if ($kpi->status !== 'pending') {
            return back()->with('error', 'This KPI has already been processed.');
        }

        $pointsToAward = $kpi->category->points_reward;
        $user = $kpi->user;

        if ($business->points < $pointsToAward) {
            return back()->with('error', 'Insufficient points balance to approve this KPI.');
        }

        try {
            DB::beginTransaction();

            // Deduct from Business
            $business->decrement('points', $pointsToAward);
            
            // Add to User
            $user->increment('points', $pointsToAward);

            // Record Transaction
            \App\Models\PointTransaction::create([
                'sender_id' => $business->id,
                'receiver_id' => $user->id,
                'amount' => $pointsToAward,
                'description' => "KPI Reward: {$kpi->category->name}",
            ]);

            // Update KPI Status
            $kpi->update([
                'status' => 'approved',
                'rewarded_points' => $pointsToAward,
            ]);

            DB::commit();

            return back()->with('success', 'KPI approved and points awarded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process KPI. Please try again.');
        }
    }

    /**
     * Reject the KPI.
     */
    public function reject(Kpi $kpi)
    {
        $business = auth()->user();

        if ($kpi->category->business_id !== $business->id) {
            abort(403);
        }

        if ($kpi->status !== 'pending') {
            return back()->with('error', 'This KPI has already been processed.');
        }

        $kpi->update(['status' => 'rejected']);

        return back()->with('success', 'KPI has been rejected.');
    }
}
