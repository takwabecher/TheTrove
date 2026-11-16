<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BoutiqueOrderDeclinedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $commande;
    protected $boutique;

    public function __construct($commande, $boutique)
    {
        $this->commande = $commande;
        $this->boutique = $boutique;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Update — TheTrove')
            ->view('emails.client-order-declined', [
                'client' => $notifiable,
                'commande' => $this->commande,
                'boutique' => $this->boutique,
            ]);
    }
}