<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Claim;

class ClaimProcessed extends Notification
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
        $status = ucfirst($this->claim->status);
        return [
            'claim_id' => $this->claim->id,
            'title' => "Claim {$status}",
            'message' => "Your claim '{$this->claim->title}' has been {$this->claim->status}.",
            'url' => route($notifiable->hasRole('employee') ? 'employee.claims.index' : 'customer.claims.index'),
            'type' => 'claim',
        ];
    }
}
