<?php

namespace App\Actions\Couple;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use Illuminate\Support\Facades\DB;

class DissolveCouple
{
    public function handle(Couple $couple): Couple
    {
        return DB::transaction(function () use ($couple) {
            $lockedCouple = Couple::query()
                ->whereKey($couple->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $lockedCouple->forceFill([
                'status' => CoupleStatus::Dissolved,
            ])->save();

            $lockedCouple->memberships()
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $lockedCouple->invites()
                ->whereNull('used_at')
                ->delete();

            return $lockedCouple->fresh();
        });
    }
}
