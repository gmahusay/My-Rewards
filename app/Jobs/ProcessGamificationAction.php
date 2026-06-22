<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Gamification\GamificationService;

class ProcessGamificationAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;
    public string $eventName;
    public array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, string $eventName, array $payload = [])
    {
        $this->userId = $userId;
        $this->eventName = $eventName;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(GamificationService $gamificationService): void
    {
        $gamificationService->handleEvent($this->userId, $this->eventName, $this->payload);
    }
}
