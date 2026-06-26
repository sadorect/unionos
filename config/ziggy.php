<?php

return [
    /*
     * Expose only user-facing named routes to the client-side Ziggy bundle.
     * System/health/metrics routes are intentionally excluded.
     */
    'except' => [
        'system.health',
        'system.metrics',
    ],
];
