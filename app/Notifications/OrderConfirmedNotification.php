<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $commande;
    protected $items;

    public function __construct($commande, $items)
    {
        $this->commande = $commande;
        $this->items = $items;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Order Received — TheTrove')
            ->view('emails.order-confirmed', [
                'boutique' => $notifiable,
                'commande' => $this->commande,
                'items' => $this->items,
            ]);
    }
}