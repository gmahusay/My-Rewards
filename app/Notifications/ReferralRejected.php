<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Referral;

class ReferralRejected extends Notification
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
            'title' => 'Referral Update',
            'message' => "Your referral for {$this->referral->referred_email} was declined.",
            'url' => route($notifiable->hasRole('employee') ? 'employee.referrals.show' : 'customer.referrals.show', $this->referral->category_id),
            'type' => 'referral_rejected',
        ];
    }
}
