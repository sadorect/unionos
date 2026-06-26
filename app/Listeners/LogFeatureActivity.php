<?php

namespace App\Listeners;

use App\Events\FeatureActivityOccurred;
use App\Support\Observability\PrivacySafeLogger;

class LogFeatureActivity
{
    public function __construct(
        private readonly PrivacySafeLogger $logger,
    ) {
    }

    public function handle(FeatureActivityOccurred $event): void
    {
        $context = array_merge($event->context, [
            'feature' => $event->feature,
            'action' => $event->action,
        ]);

        $this->logger->feature('unionos.feature_activity', $context);
        $this->logger->metric('unionos.feature_metric', $context);
    }
}
