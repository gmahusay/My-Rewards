<?php

namespace App\Listeners;

use Illuminate\Events\Dispatcher;
use App\Jobs\ProcessGamificationAction;

class GamificationEventSubscriber
{
    /**
     * Handle user creation.
     */
    public function handleUserRegistered($event): void
    {
        $user = $event->user ?? (isset($event->model) ? $event->model : $event);
        if ($user && isset($user->id)) {
            ProcessGamificationAction::dispatch($user->id, 'user.registered', []);
        }
    }

    /**
     * Handle order creation.
     */
    public function handleOrderPlaced($event): void
    {
        $order = $event->order ?? (isset($event->model) ? $event->model : clone $event);
        if ($order && isset($order->user_id)) {
            ProcessGamificationAction::dispatch($order->user_id, 'order.placed', [
                'order_id' => $order->id,
            ]);
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            // Example mappings - assuming Laravel standard auth events and eloquent events
            \Illuminate\Auth\Events\Registered::class => 'handleUserRegistered',
            'eloquent.created: App\Models\User' => 'handleUserRegistered',
            'eloquent.created: App\Models\Order' => 'handleOrderPlaced',
        ];
    }
}
