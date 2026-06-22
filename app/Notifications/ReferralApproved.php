<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Referral;

class ReferralApproved extends Notification
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
            'points_awarded' => $this->referral->rewarded_points,
            'title' => 'Referral Approved!',
            'message' => "Your referral for {$this->referral->referred_email} was approved! You earned {$this->referral->rewarded_points} points.",
            'url' => route($notifiable->hasRole('employee') ? 'employee.referrals.index' : 'customer.referrals.index'),
            'type' => 'referral_approved',
        ];
    }
}
