<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Referral;

class NewReferralSubmitted extends Notification
{
    use Queueable;

    protected $referral;

    public function __construct(Referral $referral)
    {
        $this->referral = $referral;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'referral_id' => $this->referral->id,
            'referrer_name' => $this->referral->referrer->name,
            'title' => 'New Referral Received',
            'message' => "{$this->referral->referrer->name} referred {$this->referral->referred_email} for \"{$this->referral->category->name}\".",
            'url' => route('business.referrals.index'),
            'type' => 'referral_submitted',
        ];
    }
}
