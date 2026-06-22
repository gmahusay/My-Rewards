<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ReferralCategory;

class NewReferralCampaign extends Notification
{
    use Queueable;

    protected $category;

    public function __construct(ReferralCategory $category)
    {
        $this->category = $category;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'category_id' => $this->category->id,
            'business_name' => $this->category->business->name,
            'title' => 'New Referral Campaign!',
            'message' => "{$this->category->business->name} launched: {$this->category->name}. Earn {$this->category->points_reward} pts per referral!",
            'url' => route($notifiable->hasRole('employee') ? 'employee.referrals.index' : 'customer.referrals.index'),
            'type' => 'referral_campaign',
        ];
    }
}
