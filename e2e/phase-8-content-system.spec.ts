import { expect, test, type Page } from '@playwright/test';

test('phase 8 content system previews structured packs before a session begins', async ({ page, baseURL }) => {
    await login(page, 'taylor@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();

    await page.goto(`${baseURL}/conversations`);
    await expect(page.getByRole('heading', { name: 'Conversation packs' })).toBeVisible();
    await expect(page.getByText('Weekly Reset')).toBeVisible();
    await expect(page.getByText('10-15 min')).toBeVisible();
    await expect(page.getByText('4 prompts')).toHaveCount(3);

    await page.getByRole('link', { name: 'Preview' }).first().click();
    await expect(page.getByRole('heading', { name: 'Weekly Reset' })).toBeVisible();
    await expect(page.getByText('Reset the week')).toBeVisible();
    await expect(page.getByText('Partner A').first()).toBeVisible();

    await page.getByRole('button', { name: 'Start conversation' }).click();
    await expect(page.getByRole('heading', { name: 'Before you begin' })).toBeVisible();
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
