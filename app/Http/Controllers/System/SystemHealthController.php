<?php

namespace App\Http\Controllers\System;

use App\Domains\System\Services\OperationalMetricsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SystemHealthController extends Controller
{
    public function __construct(
        private readonly OperationalMetricsService $metrics,
    ) {
    }

    public function show(): JsonResponse
    {
        $payload = $this->metrics->health();

        return response()->json($payload, $payload['status'] === 'ok' ? 200 : 503);
    }
}
