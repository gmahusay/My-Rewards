<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Kpi;

class NewKpiSubmitted extends Notification
{
    use Queueable;

    protected $kpi;

    public function __construct(Kpi $kpi)
    {
        $this->kpi = $kpi;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'kpi_id' => $this->kpi->id,
            'user_name' => $this->kpi->user->name,
            'category_name' => $this->kpi->category->name,
            'title' => 'New KPI Submission',
            'message' => "{$this->kpi->user->name} submitted a KPI for '{$this->kpi->category->name}'",
            'url' => route('business.kpis.categories.show', $this->kpi->category_id),
            'type' => 'kpi_submitted',
        ];
    }
}
