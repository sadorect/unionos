import { expect, test, type Page } from '@playwright/test';

test('phase 6 access states render calm privacy and setup boundaries', async ({ page, baseURL }) => {
    await login(page, 'sam@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Create your couple space' })).toBeVisible();
    await page.goto(`${baseURL}/check-ins/weekly`);
    await expect(page.getByRole('heading', { name: 'Create your couple space first' })).toBeVisible();

    await page.goto(`${baseURL}/couple/create`);
    await page.getByRole('button', { name: 'Create couple space' }).click();
    await expect(page.getByRole('heading', { name: 'Your couple space is waiting' })).toBeVisible();

    await page.goto(`${baseURL}/check-ins/weekly`);
    await expect(page.getByRole('heading', { name: 'Your couple space is still being set up' })).toBeVisible();

    await page.goto(`${baseURL}/users/1/progress`);
    await expect(page.getByRole('heading', { name: 'This content is private' })).toBeVisible();
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
