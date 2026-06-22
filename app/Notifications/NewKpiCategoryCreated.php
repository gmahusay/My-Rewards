<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\KpiCategory;

class NewKpiCategoryCreated extends Notification
{
    use Queueable;

    protected $category;

    public function __construct(KpiCategory $category)
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
            'title' => 'New KPI Goal Available',
            'message' => "New KPI goal '{$this->category->name}' is now available. Earn {$this->category->points_reward} points!",
            'url' => route($notifiable->hasRole('employee') ? 'employee.kpis.show' : 'customer.kpis.show', $this->category->id),
            'type' => 'kpi_category_created',
        ];
    }
}
