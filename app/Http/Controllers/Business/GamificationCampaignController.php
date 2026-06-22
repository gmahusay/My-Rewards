<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Gamification\Campaign;
use App\Models\Gamification\CampaignTarget;
use Illuminate\Http\Request;

class GamificationCampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('business_id', auth()->id())
            ->withCount('participants')
            ->latest()
            ->paginate(10);

        $metrics = [
            'total'      => Campaign::where('business_id', auth()->id())->count(),
            'active'     => Campaign::where('business_id', auth()->id())->where('is_active', true)->count(),
            'total_joins'=> \App\Models\Gamification\CampaignParticipant::whereHas('campaign', fn($q) => $q->where('business_id', auth()->id()))->count(),
        ];

        return view('gamification.business.index', compact('campaigns', 'metrics'));
    }

    public function create()
    {
        $targetTypes = CampaignTarget::$types;
        $icons = [
            'fa-solid fa-star' => 'Star',
            'fa-solid fa-trophy' => 'Trophy',
            'fa-solid fa-medal' => 'Medal',
            'fa-regular fa-gem' => 'Diamond',
            'fa-solid fa-gift' => 'Gift',
            'fa-solid fa-fire' => 'Fire',
            'fa-solid fa-bullseye' => 'Target',
            'fa-solid fa-rocket' => 'Rocket',
            'fa-solid fa-crown' => 'Crown',
            'fa-solid fa-bolt' => 'Lightning',
        ];
        $products = \App\Models\Product::where('business_id', auth()->id())->get();
        return view('gamification.business.create', compact('targetTypes', 'icons', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'logo'           => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg,webp', 'max:2048'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'reward_points'  => ['required', 'integer', 'min:0'],
            'targets'        => ['required', 'array', 'min:1'],
            'targets.*.type' => ['required', 'in:purchase,referral,nomination,claim'],
            'targets.*.value'=> ['required', 'integer', 'min:1'],
            'targets.*.icon' => ['nullable', 'string', 'max:255'],
            'targets.*.label'=> ['nullable', 'string', 'max:255'],
            'targets.*.product_id' => ['nullable', 'exists:products,id'],
        ]);

        $data = [
            'business_id'   => auth()->id(),
            'title'         => $request->title,
            'description'   => $request->description,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'reward_points' => $request->reward_points,
            'is_active'     => true,
        ];

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('gamification/logos', 'public');
        }

        $campaign = Campaign::create($data);

        foreach ($request->targets as $index => $target) {
            $campaign->targets()->create([
                'level'        => $index + 1,
                'icon'         => $target['icon'] ?? null,
                'target_type'  => $target['type'],
                'product_id'   => $target['product_id'] ?? null,
                'label'        => $target['label'] ?? null,
                'target_value' => $target['value'],
            ]);
        }

        return redirect()->route('business.gamification.index')
            ->with('status', 'Gamification campaign created successfully!');
    }

    public function show(Campaign $campaign)
    {
        $this->authorizeOwner($campaign);
        $campaign->load(['targets', 'participants.user', 'participants.progress.target']);
        return view('gamification.business.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $this->authorizeOwner($campaign);
        $campaign->load('targets');
        $targetTypes = CampaignTarget::$types;
        $icons = [
            'fa-solid fa-star' => 'Star',
            'fa-solid fa-trophy' => 'Trophy',
            'fa-solid fa-medal' => 'Medal',
            'fa-regular fa-gem' => 'Diamond',
            'fa-solid fa-gift' => 'Gift',
            'fa-solid fa-fire' => 'Fire',
            'fa-solid fa-bullseye' => 'Target',
            'fa-solid fa-rocket' => 'Rocket',
            'fa-solid fa-crown' => 'Crown',
            'fa-solid fa-bolt' => 'Lightning',
        ];
        $products = \App\Models\Product::where('business_id', auth()->id())->get();
        return view('gamification.business.edit', compact('campaign', 'targetTypes', 'icons', 'products'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorizeOwner($campaign);

        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'logo'           => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg,webp', 'max:2048'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'reward_points'  => ['required', 'integer', 'min:0'],
            'is_active'      => ['required', 'boolean'],
            'targets'        => ['required', 'array', 'min:1'],
            'targets.*.type' => ['required', 'in:purchase,referral,nomination,claim'],
            'targets.*.value'=> ['required', 'integer', 'min:1'],
            'targets.*.icon' => ['nullable', 'string', 'max:255'],
            'targets.*.label'=> ['nullable', 'string', 'max:255'],
            'targets.*.product_id' => ['nullable', 'exists:products,id'],
        ]);

        $data = [
            'title'         => $request->title,
            'description'   => $request->description,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'reward_points' => $request->reward_points,
            'is_active'     => $request->is_active,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($campaign->logo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($campaign->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('gamification/logos', 'public');
        }

        $campaign->update($data);

        // Replace all targets
        $campaign->targets()->delete();
        foreach ($request->targets as $index => $target) {
            $campaign->targets()->create([
                'level'        => $index + 1,
                'icon'         => $target['icon'] ?? null,
                'target_type'  => $target['type'],
                'product_id'   => $target['product_id'] ?? null,
                'label'        => $target['label'] ?? null,
                'target_value' => $target['value'],
            ]);
        }

        // Re-initialize progress for existing participants (since cascade delete removed them)
        $campaign->load('targets', 'participants');
        foreach ($campaign->participants as $participant) {
            foreach ($campaign->targets as $target) {
                \App\Models\Gamification\CampaignProgress::create([
                    'participant_id' => $participant->id,
                    'target_id'      => $target->id,
                    'current_value'  => 0,
                    'is_completed'   => false,
                ]);
            }
            // Reset participant completion status since targets changed
            $participant->update(['is_completed' => false, 'completed_at' => null]);
        }

        return redirect()->route('business.gamification.index')
            ->with('status', 'Campaign updated successfully!');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorizeOwner($campaign);
        if ($campaign->logo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($campaign->logo_path);
        }
        $campaign->delete();
        return redirect()->route('business.gamification.index')
            ->with('status', 'Campaign deleted successfully!');
    }

    private function authorizeOwner(Campaign $campaign): void
    {
        if ($campaign->business_id !== auth()->id()) {
            abort(403);
        }
    }
}
