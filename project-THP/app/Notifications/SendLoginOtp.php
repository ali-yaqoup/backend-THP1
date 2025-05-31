<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendLoginOtp extends Notification
{
    use Queueable;

    public $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('رمز التحقق لتسجيل الدخول')
            ->line("رمز التحقق الخاص بك هو: {$this->otp}")
            ->line('الرمز صالح لمدة 10 دقائق.');
    }

    /**
     * Get the array representation of the notification (اختياري).
     */
    public function toArray($notifiable): array
    {
        return [
            'otp' => $this->otp
        ];
    }
}
