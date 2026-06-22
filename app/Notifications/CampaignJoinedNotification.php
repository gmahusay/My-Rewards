<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Gamification\Campaign;
use App\Models\User;

class CampaignJoinedNotification extends Notification
{
    use Queueable;

    protected $campaign;
    protected $participant;

    /**
     * Create a new notification instance.
     */
    public function __construct(Campaign $campaign, User $participant)
    {
        $this->campaign = $campaign;
        $this->participant = $participant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Campaign Participant',
            'message' => "{$this->participant->name} has joined your gamification campaign: {$this->campaign->title}.",
            'url' => route('business.gamification.show', $this->campaign),
            'icon' => 'user-plus',
        ];
    }
}
