<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountReactivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isBoutique = $notifiable->role === 'boutique';

        return (new MailMessage)
            ->subject('Welcome Back — Your Account is Reactivated!')
            ->view('emails.account-reactivated', [
                'user' => $notifiable,
                'isBoutique' => $isBoutique,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your account has been reactivated on TheTrove.',
            'reactivated_at' => now()->toDateTimeString(),
        ];
    }
}