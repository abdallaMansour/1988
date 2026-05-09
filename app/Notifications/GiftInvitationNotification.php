<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GiftInvitationNotification extends Notification
{
    public function __construct(
        public string $claimUrl,
        public string $itemTitle,
        public string $gifterName,
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
            ->subject('هدية لك من '.$this->gifterName)
            ->greeting('مرحباً!')
            ->line($this->gifterName.' أرسل لك هدية: '.$this->itemTitle)
            ->line('اضغط على الرابط أدناه لتسجيل الدخول أو إنشاء حساب وقبول الهدية.')
            ->action('قبول الهدية', $this->claimUrl)
            ->line('إذا لم تكن تتوقع هذه الرسالة يمكنك تجاهلها.');
    }
}
