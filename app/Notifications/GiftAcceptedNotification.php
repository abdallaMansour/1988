<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GiftAcceptedNotification extends Notification
{
    public function __construct(
        public string $recipientName,
        public string $itemTitle,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم قبول الهدية')
            ->greeting('مرحباً '.$notifiable->name.'،')
            ->line('أبلغناك أن '.$this->recipientName.' قبل الهدية: '.$this->itemTitle)
            ->line('يمكنه الآن الوصول إلى المحتوى من قسم المشتريات في حسابه.');
    }
}
