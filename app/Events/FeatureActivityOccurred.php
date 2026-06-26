<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeatureActivityOccurred
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $feature,
        public readonly string $action,
        public readonly array $context = [],
    ) {
    }
}
