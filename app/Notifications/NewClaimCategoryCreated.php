<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\ClaimCategory;
use Illuminate\Support\Facades\Auth;

class NewClaimCategoryCreated extends Notification
{
    use Queueable;

    protected $category;

    public function __construct(ClaimCategory $category)
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
            'title' => 'New Claim Category Available',
            'message' => "{$this->category->business->name} has added a new claim category: {$this->category->name} ({$this->category->points_reward} pts).",
            'url' => route($notifiable->hasRole('employee') ? 'employee.claims.index' : 'customer.claims.index'),
            'type' => 'claim_category',
        ];
    }
}
