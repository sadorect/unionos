<?php

namespace App\Console\Commands;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\Conversation\Enums\ConversationSessionStatus;
use App\Domains\Conversation\Models\ConversationSession;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class AdvanceSystemStateCommand extends Command
{
    protected $signature = 'unionos:advance-system-state';

    protected $description = 'Archive incomplete monthly periods and mark stale conversations incomplete.';

    public function handle(): int
    {
        $currentMonth = CarbonImmutable::now()->format('Y-m');

        MonthlyCoupleCheckIn::query()
            ->whereNull('archived_at')
            ->where('month', '<', $currentMonth)
            ->get()
            ->groupBy(fn (MonthlyCoupleCheckIn $checkIn) => $checkIn->couple_id.'|'.$checkIn->month)
            ->each(function ($group): void {
                if ($group->count() < 2) {
                    MonthlyCoupleCheckIn::query()
                        ->whereIn('id', $group->pluck('id'))
                        ->update(['archived_at' => now()]);
                }
            });

        ConversationSession::query()
            ->whereIn('status', [
                ConversationSessionStatus::Active->value,
                ConversationSessionStatus::Paused->value,
            ])
            ->where('last_activity_at', '<', CarbonImmutable::now()->subHours((int) config('unionos.conversations.stale_after_hours', 24)))
            ->update([
                'status' => ConversationSessionStatus::Abandoned->value,
            ]);

        $this->components->info('System state advanced.');

        return self::SUCCESS;
    }
}
