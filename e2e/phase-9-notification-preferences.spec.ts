import { expect, test, type Page } from '@playwright/test';

test('phase 9 notification preferences allow reminders to be paused safely', async ({ page, baseURL }) => {
    await login(page, 'taylor@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();
    await expect(page.getByRole('heading', { name: "Ready for this week's check-in?" })).toBeVisible();

    await page.goto(`${baseURL}/settings/notifications`);
    await expect(page.getByRole('heading', { name: 'Notification preferences' })).toBeVisible();
    await page.getByLabel('Pause all reminders').check();
    await page.getByRole('button', { name: 'Save preferences' }).click();

    await expect(page.getByRole('heading', { name: 'Reminders are paused' })).toBeVisible();
    await page.getByRole('link', { name: 'Return home' }).click();

    await expect(page.getByText('All optional reminders are currently paused.')).toBeVisible();
    await expect(page.getByRole('heading', { name: "Ready for this week's check-in?" })).toHaveCount(0);
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
