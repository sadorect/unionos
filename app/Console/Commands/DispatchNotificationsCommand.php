<?php

namespace App\Console\Commands;

use App\Domains\Notification\Services\NotificationEligibilityService;
use App\Jobs\SendUnionNotificationJob;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class DispatchNotificationsCommand extends Command
{
    protected $signature = 'unionos:dispatch-notifications';

    protected $description = 'Dispatch eligible reminder emails using queue jobs.';

    public function __construct(
        private readonly NotificationEligibilityService $eligibility,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $now = CarbonImmutable::now();
        $count = 0;

        User::query()->get()->each(function (User $user) use ($now, &$count): void {
            $kind = $this->eligibility->nextDueNotification($user, $now);

            if ($kind === null) {
                return;
            }

            SendUnionNotificationJob::dispatch($user->id, $kind);
            $count++;
        });

        $this->components->info("Queued {$count} notification jobs.");

        return self::SUCCESS;
    }
}
