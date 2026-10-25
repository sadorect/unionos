<?php

namespace App\Mail;

use App\Domains\Couple\Models\CoupleInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoupleInviteMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public string $acceptUrl;

    public function __construct(
        public CoupleInvite $invite,
        public ?string $inviterName = null,
    ) {
        $this->acceptUrl = route('invite.accept.show', $invite->token);
    }

    public function envelope(): Envelope
    {
        $inviter = $this->inviterName ? $this->inviterName.' invited you to ' : 'You are invited to ';

        return new Envelope(
            subject: $inviter.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.couple-invite',
            with: [
                'acceptUrl' => $this->acceptUrl,
                'inviterName' => $this->inviterName,
                'expiresAt' => $this->invite->expires_at,
                'appName' => config('app.name'),
            ],
        );
    }
}
