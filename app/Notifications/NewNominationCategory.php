<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\NominationCategory;

class NewNominationCategory extends Notification
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
            'title' => 'New Nomination Open!',
            'message' => "A new nomination category '{$this->category->name}' has been created. Nominate a colleague now!",
            'url' => route('employee.nominations.index'),
            'type' => 'nomination',
        ];
    }
}
