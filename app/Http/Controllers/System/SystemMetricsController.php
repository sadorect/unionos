<?php

namespace App\Http\Controllers\System;

use App\Domains\System\Services\OperationalMetricsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SystemMetricsController extends Controller
{
    public function __construct(
        private readonly OperationalMetricsService $metrics,
    ) {
    }

    public function show(): JsonResponse
    {
        return response()->json($this->metrics->metrics());
    }
}
