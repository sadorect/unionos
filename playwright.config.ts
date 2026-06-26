import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: './e2e',
    timeout: 60_000,
    workers: 1,
    expect: {
        timeout: 10_000,
    },
    use: {
        baseURL: 'http://127.0.0.1:8000',
        trace: 'retain-on-failure',
    },
    webServer: {
        command: 'powershell -Command "$env:UNIONOS_AUTH_RATE_LIMIT=50; npm run build; php artisan migrate:fresh; php artisan unionos:seed-e2e phase-3; php artisan serve --host=127.0.0.1 --port=8000"',
        url: 'http://127.0.0.1:8000',
        timeout: 120_000,
        reuseExistingServer: false,
    },
});
