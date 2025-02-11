<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('Notifikasi Reset Password'))
            ->line(Lang::get('Kamu Menerima pesan ini karena kamu ingin me-reset password.'))
            ->line(Lang::get('Untuk tokennya adalah: ' . $this->token))
            ->line(Lang::get('Jangan lupa masukan kedalam websitenya yah!!'));
    }
}