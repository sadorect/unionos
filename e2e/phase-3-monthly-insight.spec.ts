import { expect, test, type Page } from '@playwright/test';

test('phase 3 monthly insight reveals only after both partners submit', async ({ browser, page, baseURL }) => {
    await login(page, 'alex@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();

    await page.goto(`${baseURL}/check-ins/weekly`);
    await submitWeeklyCheckIn(page, [4, 4, 2]);
    await expect(page.getByRole('heading', { name: 'Check-in saved' })).toBeVisible();

    await page.goto(`${baseURL}/check-ins/monthly`);
    await submitMonthlyCheckIn(page, [4, 4, 4, 4]);
    await expect(page.getByRole('heading', { name: 'Check-in saved' })).toBeVisible();
    await expect(page.getByText('Nothing is shared until both submissions are complete.')).toBeVisible();

    const partnerContext = await browser.newContext();
    const partnerPage = await partnerContext.newPage();

    await login(partnerPage, 'jamie@example.com', 'password');
    await expect(partnerPage.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();

    await partnerPage.goto(`${baseURL}/check-ins/monthly`);
    await submitMonthlyCheckIn(partnerPage, [2, 4, 2, 4]);
    await expect(partnerPage.getByRole('heading', { name: 'Monthly alignment' })).toBeVisible();
    await expect(partnerPage.getByText('Feeling heard')).toBeVisible();

    await page.goto(`${baseURL}/check-ins/monthly`);
    await expect(page.getByRole('heading', { name: 'Monthly alignment' })).toBeVisible();
    await expect(page.locator('span', { hasText: /^needs conversation$/i })).toHaveCount(2);
    await expect(page.locator('span', { hasText: /^aligned$/i })).toHaveCount(2);
    await expect(page.getByText('You feel similarly in this area.')).toHaveCount(2);
    await expect(page.getByText('You may feel differently in this area.')).toHaveCount(2);

    await partnerContext.close();
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

async function submitWeeklyCheckIn(page: Page, values: number[]) {
    await expect(page.getByRole('heading', { name: 'Weekly check-in' })).toBeVisible();
    await clickScaleRow(page, 'emotional_availability', values[0]);
    await clickScaleRow(page, 'communication_effort', values[1]);
    await clickScaleRow(page, 'stress_level', values[2]);
    await page.getByRole('button', { name: 'Save check-in' }).click();
}

async function submitMonthlyCheckIn(page: Page, values: number[]) {
    await expect(page.getByRole('heading', { name: 'Monthly check-in' })).toBeVisible();
    await clickScaleRow(page, 'felt_heard', values[0]);
    await clickScaleRow(page, 'felt_appreciated', values[1]);
    await clickScaleRow(page, 'conflict_safety', values[2]);
    await clickScaleRow(page, 'emotional_closeness', values[3]);
    await page.getByRole('button', { name: 'Submit check-in' }).click();
}

async function clickScaleRow(page: Page, field: string, value: number) {
    await page.locator(`button[name="${field}"]`).nth(value - 1).click();
}
