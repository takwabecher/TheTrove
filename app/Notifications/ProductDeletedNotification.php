<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $produit;
    protected $reason;

    public function __construct($produit, $reason = null)
    {
        $this->produit = $produit;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Treasure Removed — TheTrove')
            ->view('emails.product-deleted', [
                'boutique' => $notifiable,
                'produit' => $this->produit,
                'reason' => $this->reason,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your product has been deleted from TheTrove.',
            'product_id' => $this->produit->id_product,
            'product_name' => $this->produit->nom,
            'deleted_at' => now()->toDateTimeString(),
        ];
    }
}