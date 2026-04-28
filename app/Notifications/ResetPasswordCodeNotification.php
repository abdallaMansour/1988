<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordCodeNotification extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $code
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Password Reset Code'))
            ->greeting(__('Hello!'))
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->line(__('Your verification code is: **:code**', ['code' => $this->code]))
            ->line(__('This code will expire in 15 minutes.'))
            ->line(__('If you did not request a password reset, no further action is required.'));
    }
}
