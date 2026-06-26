import { expect, test, type Page } from '@playwright/test';

test('phase 5 dashboards show private and shared progress views', async ({ browser, page, baseURL }) => {
    await login(page, 'alex@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();

    await page.goto(`${baseURL}/check-ins/weekly`);
    await submitWeeklyCheckIn(page, [4, 3, 2]);
    await expect(page.getByRole('heading', { name: 'Check-in saved' })).toBeVisible();

    await page.goto(`${baseURL}/check-ins/monthly`);
    await submitMonthlyCheckIn(page, [4, 4, 4, 4]);
    await expect(
        page.getByRole('heading', { name: /Check-in saved|Monthly alignment/ }),
    ).toBeVisible();

    const partnerContext = await browser.newContext();
    const partnerPage = await partnerContext.newPage();
    await login(partnerPage, 'jamie@example.com', 'password');
    await expect(partnerPage.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();
    await partnerPage.goto(`${baseURL}/check-ins/monthly`);
    await submitMonthlyCheckIn(partnerPage, [2, 4, 4, 4]);
    await expect(partnerPage.getByRole('heading', { name: 'Monthly alignment' })).toBeVisible();

    await page.goto(`${baseURL}/dashboard/personal`);
    await expect(page.getByRole('heading', { name: 'My progress' })).toBeVisible();
    await expect(page.getByText('Weekly check-ins completed')).toBeVisible();
    await expect(page.getByText('1 completed')).toBeVisible();

    await page.goto(`${baseURL}/dashboard/couple`);
    await expect(page.getByRole('heading', { name: 'Our progress' })).toBeVisible();
    await expect(page.getByText('Monthly alignment trend')).toBeVisible();
    await expect(page.getByText(/March/)).toBeVisible();
    await expect(page.getByText(/mixed/i)).toHaveCount(2);

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

    const saveButton = page.getByRole('button', { name: 'Save check-in' });
    const updateButton = page.getByRole('button', { name: 'Update check-in' });

    if (await saveButton.isVisible()) {
        await saveButton.click();

        return;
    }

    await updateButton.click();
}

async function submitMonthlyCheckIn(page: Page, values: number[]) {
    if (await page.getByRole('heading', { name: 'Monthly alignment' }).isVisible()) {
        return;
    }

    if (await page.getByRole('heading', { name: 'Check-in saved' }).isVisible()) {
        return;
    }

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
