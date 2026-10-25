<?php

namespace App\Actions\Couple;

use App\Domains\Couple\Models\CoupleInvite;
use App\Mail\CoupleInviteMail;
use Illuminate\Support\Facades\Mail;

class SendCoupleInvite
{
    public function handle(CoupleInvite $invite): void
    {
        $inviterName = $invite->couple
            ->memberships()
            ->with('user')
            ->first()?->user?->name;

        Mail::to($invite->email)->queue(new CoupleInviteMail($invite, $inviterName));
    }
}
