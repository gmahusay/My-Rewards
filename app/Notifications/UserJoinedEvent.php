<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use App\Models\User;

class UserJoinedEvent extends Notification
{
    use Queueable;

    protected $event;
    protected $user;

    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'user_id' => $this->user->id,
            'title' => 'User Joined Event',
            'message' => "{$this->user->name} has just joined the event '{$this->event->title}'.",
            'url' => route('business.events.participants', $this->event->id),
            'type' => 'event',
        ];
    }
}
