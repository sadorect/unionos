import { expect, test, type BrowserContext, type Page } from '@playwright/test';

test('phase 7 lifecycle states handle paused, completed, and dissolved flows', async ({ browser, page, baseURL }) => {
    await login(page, 'alex@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();

    const partnerContext = await browser.newContext();
    const partnerPage = await partnerContext.newPage();
    await login(partnerPage, 'jamie@example.com', 'password');
    await expect(partnerPage.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();

    try {
        const sessionUrl = await startConversation(page, baseURL);

        await respondInConversation(page, sessionUrl, 'I felt steadier when we slowed down our evening plans.');
        await respondInConversation(partnerPage, sessionUrl, 'I hear that the slower pace made the week feel calmer.');

        await page.goto(sessionUrl);
        await page.getByRole('button', { name: 'Pause' }).click();
        await expect(page.getByRole('heading', { name: 'Conversation paused' })).toBeVisible();

        await page.goto(sessionUrl);
        await expect(page.getByRole('heading', { name: 'Conversation paused' })).toBeVisible();

        await page.getByRole('button', { name: 'Resume conversation' }).nth(1).click();
        await expect(page.getByRole('textbox')).toBeVisible();

        await respondInConversation(page, sessionUrl, 'Next week I want to ask for quiet before dinner sooner.');
        await respondInConversation(partnerPage, sessionUrl, 'I can take the lead on cleanup so that happens.');
        await respondInConversation(page, sessionUrl, 'I appreciate how direct you were when checking in.');
        await respondInConversation(partnerPage, sessionUrl, 'I appreciate how clear you were about what you needed.');
        await expect(partnerPage.getByRole('heading', { name: 'Conversation complete' })).toBeVisible();

        await page.goto(sessionUrl);
        await expect(page.getByRole('heading', { name: 'This conversation is complete' })).toBeVisible();

        await page.goto(`${baseURL}/home`);
        await page.getByRole('button', { name: 'Dissolve couple space' }).click();
        await expect(page.getByRole('heading', { name: 'This couple space is no longer active' })).toBeVisible();

        await page.goto(`${baseURL}/check-ins/weekly`);
        await expect(page.getByRole('heading', { name: 'This couple space is no longer active' })).toBeVisible();
    } finally {
        await closeContext(partnerContext);
    }
});

async function startConversation(page: Page, baseURL: string | undefined) {
    await page.goto(`${baseURL}/conversations`);
    await expect(page.getByRole('heading', { name: 'Conversation packs' })).toBeVisible();
    await page.getByRole('button', { name: 'Start' }).first().click();
    await expect(page.getByRole('heading', { name: 'Before you begin' })).toBeVisible();
    await page.getByRole('checkbox').check();
    await page.getByRole('button', { name: 'Begin conversation' }).click();

    return page.url();
}

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

async function respondInConversation(page: Page, sessionUrl: string, text: string) {
    await page.goto(sessionUrl);
    await expect(page.getByRole('textbox')).toBeVisible();
    await page.getByRole('textbox').fill(text);

    if (await page.getByRole('button', { name: 'Submit response' }).isVisible()) {
        await page.getByRole('button', { name: 'Submit response' }).click();
        return;
    }

    await page.getByRole('button', { name: 'Complete conversation' }).click();
}

async function closeContext(context: BrowserContext) {
    await context.close();
}
