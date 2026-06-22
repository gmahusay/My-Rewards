<?php

namespace App\Services\Gamification;

use App\Models\User;
use App\Models\Gamification\Wallet;
use App\Models\Gamification\Transaction;
use App\Models\Gamification\ActivityLog;
use App\Models\Gamification\Level;
use App\Models\Gamification\UserLevel;
use App\Models\Gamification\Badge;
use App\Models\Gamification\UserBadge;
use App\Models\Gamification\Mission;
use App\Models\Gamification\UserMission;

class GamificationService
{
    /**
     * Handle incoming gamification events.
     */
    public function handleEvent(int $userId, string $eventName, array $payload = [])
    {
        $pointsConfig = config('gamification.events.' . $eventName);
        
        if ($pointsConfig) {
            $xp = $pointsConfig['xp'] ?? 0;
            $stardust = $pointsConfig['stardust'] ?? 0;

            if ($xp > 0) {
                $this->awardPoints($userId, $xp, 'xp', "Awarded XP for {$eventName}");
            }
            if ($stardust > 0) {
                $this->awardPoints($userId, $stardust, 'stardust', "Awarded Stardust for {$eventName}");
            }
        }

        $this->logActivity($userId, $eventName, $payload);
        $this->assignMissionProgress($userId, $eventName);
        $this->checkBadges($userId, $eventName);
    }

    public function awardPoints(int $userId, int $points, string $type = 'xp', string $description = '', ?string $referenceId = null)
    {
        $wallet = Wallet::firstOrCreate(['user_id' => $userId]);

        if ($type === 'xp') {
            $wallet->increment('xp_total', $points);
            $this->checkLevelUp($userId);
        } else {
            $wallet->increment('stardust_balance', $points);
        }

        Transaction::create([
            'wallet_id' => $wallet->id,
            'points' => $points,
            'type' => $type,
            'description' => $description,
            'reference_id' => $referenceId,
        ]);
    }

    public function checkLevelUp(int $userId)
    {
        $wallet = Wallet::where('user_id', $userId)->first();
        if (!$wallet) return;

        $newXpTotal = $wallet->xp_total;
        $userLevel = UserLevel::firstOrCreate(['user_id' => $userId], ['level_id' => 1]);
        $currentLevelId = $userLevel->level_id;

        $nextLevel = Level::where('xp_required', '<=', $newXpTotal)
            ->orderBy('xp_required', 'desc')
            ->first();

        if ($nextLevel && $nextLevel->id > $currentLevelId) {
            $userLevel->update(['level_id' => $nextLevel->id]);
            // Dispatch level up event or log it
            $this->logActivity($userId, 'level_up', ['new_level' => $nextLevel->id]);
        }
    }

    public function logActivity(int $userId, string $actionName, array $metadata = [])
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action_name' => $actionName,
            'metadata' => $metadata,
        ]);
    }

    public function assignMissionProgress(int $userId, string $eventType, int $increment = 1)
    {
        $missions = Mission::where('event_type', $eventType)->get();

        foreach ($missions as $mission) {
            $userMission = UserMission::firstOrCreate([
                'user_id' => $userId,
                'mission_id' => $mission->id,
            ]);

            if (!$userMission->is_completed) {
                $userMission->increment('current_progress', $increment);

                if ($userMission->current_progress >= $mission->required_count) {
                    $userMission->update(['is_completed' => true]);
                    $this->awardPoints($userId, $mission->reward_points, 'xp', "Mission Completed: {$mission->title}");
                }
            }
        }
    }

    public function checkBadges(int $userId, string $eventType)
    {
        $badges = Badge::where('trigger_event', $eventType)->get();

        foreach ($badges as $badge) {
            // Check if user already has it
            $hasBadge = UserBadge::where('user_id', $userId)->where('badge_id', $badge->id)->exists();
            
            if (!$hasBadge) {
                // Here you would add specific logic to evaluate if the condition is truly met.
                // For simplicity, we award it directly if the trigger event matches.
                $this->awardBadge($userId, $badge->id);
            }
        }
    }

    public function awardBadge(int $userId, int $badgeId)
    {
        UserBadge::firstOrCreate([
            'user_id' => $userId,
            'badge_id' => $badgeId,
        ]);
        
        $this->logActivity($userId, 'badge_awarded', ['badge_id' => $badgeId]);
    }

    /**
     * Update Gamification Campaign Progress for a specific target type.
     */
    public function updateCampaignProgress(int $userId, string $targetType, int $increment = 1, ?int $productId = null)
    {
        // Find all active campaigns the user has joined where they haven't completed it yet
        $participants = \App\Models\Gamification\CampaignParticipant::where('user_id', $userId)
            ->where('is_completed', false)
            ->whereHas('campaign', function ($query) {
                $query->where('is_active', true);
            })
            ->with(['progress.target', 'campaign'])
            ->get();

        foreach ($participants as $participant) {
            $remaining = $increment;

            while ($remaining > 0) {
                // Find the lowest uncompleted level (reload from db to get fresh state)
                $currentProgress = \App\Models\Gamification\CampaignProgress::with('target')
                    ->where('participant_id', $participant->id)
                    ->where('is_completed', false)
                    ->get()
                    ->sortBy('target.level')
                    ->first();

                // If no more uncompleted levels, or the current level isn't of this target type, stop.
                if (!$currentProgress || $currentProgress->target->target_type !== $targetType) {
                    break;
                }

                // If target requires a specific product, and this event provides a product, check it
                if ($targetType === 'purchase' && $currentProgress->target->product_id) {
                    if ($currentProgress->target->product_id != $productId) {
                        break; // This specific purchase does not meet the requirement
                    }
                }

                $needed = $currentProgress->target->target_value - $currentProgress->current_value;

                if ($remaining >= $needed) {
                    // Complete this level
                    $currentProgress->update([
                        'current_value' => $currentProgress->target->target_value,
                        'is_completed'  => true,
                    ]);
                    $remaining -= $needed;
                } else {
                    // Add remaining to this level and stop
                    $currentProgress->update([
                        'current_value' => $currentProgress->current_value + $remaining,
                    ]);
                    $remaining = 0;
                }
            }

            // Check if all targets for this campaign are now completed
            // Must have a completed progress row for EVERY target (not just "no incomplete rows")
            $totalTargets = $participant->campaign->targets()->count();
            $completedCount = \App\Models\Gamification\CampaignProgress::where('participant_id', $participant->id)
                ->where('is_completed', true)
                ->count();
            
            $allCompleted = $totalTargets > 0 && $completedCount >= $totalTargets;

            if ($allCompleted && !$participant->is_completed) {
                $participant->update([
                    'is_completed' => true,
                    'completed_at' => now(),
                ]);

                // Award points
                $this->awardPoints(
                    $userId,
                    $participant->campaign->reward_points,
                    'xp',
                    "Completed Campaign: {$participant->campaign->title}",
                    'campaign_' . $participant->campaign->id
                );
            }
        }
    }
}
