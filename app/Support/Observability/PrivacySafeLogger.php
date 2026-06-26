<?php

namespace App\Support\Observability;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PrivacySafeLogger
{
    public function feature(string $message, array $context = []): void
    {
        Log::channel('unionos_privacy')->info($message, $this->sanitize($context));
    }

    public function metric(string $message, array $context = []): void
    {
        Log::channel('unionos_metrics')->info($message, $this->sanitize($context));
    }

    public function alert(string $message, array $context = []): void
    {
        Log::channel('unionos_alerts')->critical($message, $this->sanitize($context));
    }

    private function sanitize(array $context): array
    {
        return Arr::only($context, [
            'feature',
            'action',
            'status',
            'kind',
            'channel',
            'pack_type',
            'pack_version',
            'method',
            'route',
            'exception',
            'queue',
            'connection',
            'pending_jobs',
            'failed_jobs',
            'database',
        ]);
    }
}
