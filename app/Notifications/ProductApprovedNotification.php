<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $produit;

    public function __construct($produit)
    {
        $this->produit = $produit;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Treasure is Live — TheTrove')
            ->view('emails.product-approved', [
                'boutique' => $notifiable,
                'produit' => $this->produit,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your product has been approved on TheTrove.',
            'product_id' => $this->produit->id_product,
            'product_name' => $this->produit->nom,
            'approved_at' => now()->toDateTimeString(),
        ];
    }
}