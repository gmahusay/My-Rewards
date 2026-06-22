<?php

namespace App\Http\Controllers;

use App\Models\Gamification\Campaign;
use App\Models\Gamification\CampaignParticipant;
use App\Models\Gamification\CampaignProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    /**
     * Show available gamification campaigns for the logged-in user.
     * Business users see their campaign management dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('business') || $user->hasRole('admin')) {
            return redirect()->route('business.gamification.index');
        }

        // For employee / customer / referrer: show campaigns from their business
        $businessId = $user->business_id;

        $campaigns = Campaign::where('business_id', $businessId)
            ->where('is_active', true)
            ->withCount('participants')
            ->with(['targets', 'participants' => fn($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->paginate(10);

        if ($campaigns->isEmpty()) {
            session()->now('info', 'No gamification campaigns have been created yet. Please check back later!');
        }

        return view('gamification.index', compact('campaigns', 'user'));
    }

    /**
     * Join a campaign.
     */
    public function join(Campaign $campaign)
    {
        $user = Auth::user();

        if ($campaign->isJoinedBy($user)) {
            return back()->with('error', 'You have already joined this campaign.');
        }

        if (!$campaign->is_active) {
            return back()->with('error', 'This campaign is no longer active.');
        }

        $participant = CampaignParticipant::create([
            'campaign_id'  => $campaign->id,
            'user_id'      => $user->id,
            'is_completed' => false,
        ]);

        // Initialise progress rows for each target
        foreach ($campaign->targets as $target) {
            CampaignProgress::create([
                'participant_id' => $participant->id,
                'target_id'      => $target->id,
                'current_value'  => 0,
                'is_completed'   => false,
            ]);
        }

        // Notify the business owner
        $business = \App\Models\User::find($campaign->business_id);
        if ($business) {
            $business->notify(new \App\Notifications\CampaignJoinedNotification($campaign, $user));
        }

        return back()->with('status', 'You have successfully joined the campaign!');
    }

    /**
     * Show a single campaign with the user's progress.
     */
    public function show(Campaign $campaign)
    {
        $user = Auth::user();
        $campaign->load('targets');

        $participant = CampaignParticipant::where('campaign_id', $campaign->id)
            ->where('user_id', $user->id)
            ->with('progress.target')
            ->first();

        return view('gamification.show', compact('campaign', 'participant'));
    }
}
