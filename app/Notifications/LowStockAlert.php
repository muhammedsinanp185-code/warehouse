<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LowStockAlert extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $products;

    /**
     * Create a new notification instance.
     */
    public function __construct($products)
    {
        $this->products = $products;
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
        $count = count($this->products);
        $names = collect($this->products)->pluck('name')->join(', ', ' and ');

        return [
            'title' => "Low Stock Alert ($count items)",
            'message' => "The following items are low on stock: $names",
            'type' => 'warning'
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Low Stock Alert',
            'message' => collect($this->products)->pluck('name')->join(', ', ' and ') . ' are low on stock.',
        ]);
    }
}
