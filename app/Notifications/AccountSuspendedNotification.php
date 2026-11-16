<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reason;

    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isBoutique = $notifiable->role === 'boutique';

        return (new MailMessage)
            ->subject('Account Suspended — TheTrove')
            ->view('emails.account-suspended', [
                'user' => $notifiable,
                'isBoutique' => $isBoutique,
                'reason' => $this->reason,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your account has been suspended on TheTrove.',
            'suspended_at' => now()->toDateTimeString(),
        ];
    }
}