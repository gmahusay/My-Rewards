$service = app(\App\Services\Gamification\GamificationService::class);
// Get last participant
$p = \App\Models\Gamification\CampaignParticipant::with('campaign.targets', 'progress.target', 'user')->latest()->first();
echo "Testing with user: " . $p->user->email . "\n";
echo "Campaign Targets:\n";
foreach($p->campaign->targets as $t) {
    echo "  Level " . $t->level . ": " . $t->target_type . " (value: " . $t->target_value . ")\n";
}
echo "Current Progress:\n";
foreach($p->progress as $prog) {
    echo "  Level " . $prog->target->level . ": " . $prog->current_value . " / " . $prog->target->target_value . " (done: " . $prog->is_completed . ")\n";
}
echo "Running updateCampaignProgress(user, 'purchase', 5)...\n";
$service->updateCampaignProgress($p->user_id, 'purchase', 5);
echo "After Update Progress:\n";
$p->refresh();
foreach($p->progress as $prog) {
    echo "  Level " . $prog->target->level . ": " . $prog->current_value . " / " . $prog->target->target_value . " (done: " . $prog->is_completed . ")\n";
}
