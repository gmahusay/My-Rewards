$campaigns = \App\Models\Gamification\Campaign::with('participants.progress', 'targets')->get();
foreach ($campaigns as $c) {
    foreach ($c->participants as $p) {
        if ($p->progress->isEmpty()) {
            echo "Participant " . $p->id . " has no progress. Creating...\n";
            foreach ($c->targets as $t) {
                \App\Models\Gamification\CampaignProgress::create([
                    'participant_id' => $p->id,
                    'target_id'      => $t->id,
                    'current_value'  => 0,
                    'is_completed'   => false,
                ]);
            }
            $p->update(['is_completed' => false, 'completed_at' => null]);
        }
    }
}
echo "Done fixing.\n";
