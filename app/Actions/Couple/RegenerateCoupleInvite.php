<?php

namespace App\Actions\Couple;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleInvite;
use App\Domains\Couple\Services\CoupleAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegenerateCoupleInvite
{
    public function __construct(
        private readonly CoupleAccessService $access,
    ) {
    }

    public function handle(Couple $couple, string $email): CoupleInvite
    {
        if ($couple->status !== CoupleStatus::Pending || $this->access->activeMemberCount($couple) >= 2) {
            throw ValidationException::withMessages([
                'email' => 'An invitation cannot be created for this couple space.',
            ]);
        }

        return DB::transaction(function () use ($couple, $email) {
            $couple->invites()
                ->whereNull('used_at')
                ->delete();

            return $couple->invites()->create([
                'email' => $email,
                'token' => Str::random(64),
                'expires_at' => now()->addHours((int) config('unionos.invites.expires_after_hours')),
            ]);
        });
    }
}
