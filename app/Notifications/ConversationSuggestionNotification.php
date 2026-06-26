<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConversationSuggestionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('A guided conversation is available')
            ->greeting('Hello,')
            ->line('A guided conversation is available if you would like a calmer space to talk together.')
            ->action('Open conversation packs', route('conversations.index'))
            ->line('This is optional, and you can change reminder preferences at any time.');
    }
}
