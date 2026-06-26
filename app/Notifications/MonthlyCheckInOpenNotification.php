<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlyCheckInOpenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your monthly check-in is available')
            ->greeting('Hello,')
            ->line('Your monthly check-in is available if you would like to use it.')
            ->action('Open monthly check-in', route('checkins.monthly.show'))
            ->line('You can pause reminders whenever you need quiet.');
    }
}
