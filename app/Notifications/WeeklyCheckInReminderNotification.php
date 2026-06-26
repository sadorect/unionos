<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyCheckInReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A weekly check-in is available')
            ->greeting('Hello,')
            ->line('Your weekly check-in is available whenever it feels helpful.')
            ->action('Open weekly check-in', route('checkins.weekly.show'))
            ->line('You can change reminder preferences any time in your settings.');
    }
}
