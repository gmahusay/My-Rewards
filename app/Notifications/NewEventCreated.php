<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class NewEventCreated extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'title' => 'New Event Scheduled!',
            'message' => "A new event '{$this->event->title}' has been scheduled for {$this->event->event_date->format('M d, Y')}. Join now to earn {$this->event->points_reward} points!",
            'url' => route('events.index'),
            'type' => 'event',
        ];
    }
}
