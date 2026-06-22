<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Claim;

class ClaimSubmitted extends Notification
{
    use Queueable;

    protected $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'claim_id' => $this->claim->id,
            'user_name' => $this->claim->user->name,
            'title' => 'New Claim Submitted',
            'message' => "{$this->claim->user->name} has submitted a new claim for '{$this->claim->title}'.",
            'url' => route('business.claims.show', $this->claim->id),
            'type' => 'claim',
        ];
    }
}
