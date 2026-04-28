<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageReplyNotification extends Notification
{
    public function __construct(
        public ContactMessage $contactMessage,
        public ?string $recipientName = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->recipientName ?? ($notifiable->name ?? __('عزيزي العميل'));

        return (new MailMessage)
            ->subject(__('تم الرد على رسالتك'))
            ->greeting(__('مرحباً :name!', ['name' => $name]))
            ->line(__('تم الرد على رسالتك التي أرسلتها إلينا.'))
            ->line(__('رسالتك:'))
            ->line($this->contactMessage->message)
            ->line(__('ردنا:'))
            ->line($this->contactMessage->admin_reply)
            ->line(__('شكراً لتواصلك معنا.'));
    }
}
