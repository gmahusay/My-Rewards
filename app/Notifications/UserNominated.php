<?php

namespace App\Notifications;

use App\Models\Nomination;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNominated extends Notification
{
    use Queueable;

    protected $nomination;

    /**
     * Create a new notification instance.
     */
    public function __construct(Nomination $nomination)
    {
        $this->nomination = $nomination;
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
        $nominator = $this->nomination->nominator;
        $nominee = $this->nomination->nominee;
        $category = $this->nomination->category;

        return [
            'type' => 'user_nominated',
            'nomination_id' => $this->nomination->id,
            'nominator_name' => $nominator->name,
            'nominee_name' => $nominee->name,
            'category_title' => $category->title,
            'message' => "{$nominator->name} nominated {$nominee->name} for '{$category->title}'.",
            'url' => route('business.nominations.categories.results', $category->id),
        ];
    }
}
