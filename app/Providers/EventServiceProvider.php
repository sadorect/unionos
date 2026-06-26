<?php

namespace App\Providers;

use App\Events\FeatureActivityOccurred;
use App\Listeners\LogFailedQueueJob;
use App\Listeners\LogFeatureActivity;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Queue\Events\JobFailed;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        FeatureActivityOccurred::class => [
            LogFeatureActivity::class,
        ],
        JobFailed::class => [
            LogFailedQueueJob::class,
        ],
    ];
}
