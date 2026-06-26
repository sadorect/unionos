<?php

namespace App\Domains\System\Services;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Models\CoupleMembership;
use App\Domains\Notification\Models\NotificationDispatchLog;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class OperationalMetricsService
{
    public function metrics(): array
    {
        $currentWeek = CarbonImmutable::now()->startOfWeek()->toDateString();
        $currentMonth = CarbonImmutable::now()->format('Y-m');
        $activeUsers = CoupleMembership::query()->where('is_active', true)->count();
        $conversationTotal = ConversationSession::query()->count();

        return [
            'usage' => [
                'weekly_check_ins_total' => WeeklyCheckIn::query()->count(),
                'monthly_check_ins_total' => MonthlyCoupleCheckIn::query()->whereNull('archived_at')->count(),
                'conversation_sessions_total' => $conversationTotal,
                'notifications_sent_total' => NotificationDispatchLog::query()->where('status', 'sent')->count(),
            ],
            'completion_rates' => [
                'weekly_current_week' => $activeUsers > 0
                    ? round((WeeklyCheckIn::query()->whereDate('week_start_date', $currentWeek)->count() / $activeUsers) * 100, 2)
                    : 0.0,
                'monthly_current_month' => $activeUsers > 0
                    ? round((MonthlyCoupleCheckIn::query()->where('month', $currentMonth)->whereNull('archived_at')->count() / $activeUsers) * 100, 2)
                    : 0.0,
                'conversation_completion' => $conversationTotal > 0
                    ? round((ConversationSession::query()->where('status', 'completed')->count() / $conversationTotal) * 100, 2)
                    : 0.0,
            ],
            'system' => [
                'pending_jobs' => DB::table(config('queue.connections.database.table', 'jobs'))->count(),
                'failed_jobs' => DB::table(config('queue.failed.table', 'failed_jobs'))->count(),
            ],
        ];
    }

    public function health(): array
    {
        $database = $this->databaseStatus();
        $pendingJobs = 0;
        $failedJobs = 0;

        if ($database === 'ok') {
            $pendingJobs = DB::table(config('queue.connections.database.table', 'jobs'))->count();
            $failedJobs = DB::table(config('queue.failed.table', 'failed_jobs'))->count();
        }

        return [
            'status' => $database === 'ok' ? 'ok' : 'unavailable',
            'checks' => [
                'database' => $database,
                'pending_jobs' => $pendingJobs,
                'failed_jobs' => $failedJobs,
            ],
        ];
    }

    private function databaseStatus(): string
    {
        try {
            DB::select('select 1');

            return 'ok';
        } catch (\Throwable) {
            return 'unavailable';
        }
    }
}
