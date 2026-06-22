<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\NominationCategory;

class NominationAwarded extends Notification
{
    use Queueable;

    protected $category;

    public function __construct(NominationCategory $category)
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
            'title' => 'Nomination Award!',
            'message' => "Congratulations! You have been awarded the winner for '{$this->category->name}' and received {$this->category->points_reward} points.",
            'url' => route('employee.nominations.index'),
            'type' => 'nomination',
        ];
    }
}
