import { expect, test, type Page } from '@playwright/test';

test('phase 10 exposes calm fallback states and privacy safe operational endpoints', async ({ page, request, baseURL }) => {
    const health = await request.get(`${baseURL}/health/unionos`);
    expect(health.ok()).toBeTruthy();
    const healthJson = await health.json();
    expect(healthJson.status).toBe('ok');

    const metrics = await request.get(`${baseURL}/metrics/unionos`);
    expect(metrics.ok()).toBeTruthy();
    const metricsJson = await metrics.json();
    expect(metricsJson.usage).toBeTruthy();
    expect(metricsJson.completion_rates).toBeTruthy();

    await login(page, 'alex@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();

    await page.goto(`${baseURL}/state/action-failed`);
    await expect(page.getByRole('heading', { name: "We couldn't complete that action" })).toBeVisible();

    await page.goto(`${baseURL}/state/load-failed`);
    await expect(page.getByRole('heading', { name: "We couldn't load this page" })).toBeVisible();
});

async function login(page: Page, email: string, password: string) {
    await page.goto('/login');
    await page.getByLabel('Email').fill(email);
    await page.locator('#password').fill(password);
    await fillCaptcha(page);
    await page.getByRole('button', { name: 'Log in' }).click();
}

async function fillCaptcha(page: Page) {
    const text = await page.locator('text=/What is \\d+ \\+ \\d+\\?/').first().textContent();
    const match = text?.match(/What is (\d+) \+ (\d+)\?/);

    if (!match) {
        throw new Error('Captcha prompt not found');
    }

    const answer = Number(match[1]) + Number(match[2]);
    await page.locator('#captcha_answer').fill(String(answer));
}
