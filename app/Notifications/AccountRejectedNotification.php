<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejectedNotification extends Notification implements ShouldQueue
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
            ->subject('Account Application — TheTrove')
            ->view('emails.account-rejected', [
                'user' => $notifiable,
                'isBoutique' => $isBoutique,
                'reason' => $this->reason,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your account application was not approved on TheTrove.',
            'rejected_at' => now()->toDateTimeString(),
        ];
    }
}