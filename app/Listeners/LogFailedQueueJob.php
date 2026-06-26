<?php

namespace App\Listeners;

use App\Support\Observability\PrivacySafeLogger;
use Illuminate\Queue\Events\JobFailed;

class LogFailedQueueJob
{
    public function __construct(
        private readonly PrivacySafeLogger $logger,
    ) {
    }

    public function handle(JobFailed $event): void
    {
        $this->logger->alert('unionos.queue_job_failed', [
            'feature' => 'queue',
            'action' => 'failed',
            'status' => 'failed',
            'connection' => $event->connectionName,
            'queue' => $event->job->getQueue(),
            'exception' => $event->exception::class,
        ]);
    }
}
