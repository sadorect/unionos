import { expect, test, type Page } from '@playwright/test';

test('phase 4 guided conversations support turn-taking and pause resume', async ({ browser, page, baseURL }) => {
    await login(page, 'alex@example.com', 'password');
    await expect(page.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();
    await page.goto(`${baseURL}/conversations`);

    await expect(page.getByRole('heading', { name: 'Conversation packs' })).toBeVisible();
    await page.getByRole('button', { name: 'Start' }).first().click();

    await expect(page.getByRole('heading', { name: 'Before you begin' })).toBeVisible();
    await page.getByRole('checkbox').check();
    await page.getByRole('button', { name: 'Begin conversation' }).click();

    const sessionUrl = page.url();

    await respondInConversation(page, sessionUrl, 'One thing that went well for me this week was starting more slowly.');

    const partnerContext = await browser.newContext();
    const partnerPage = await partnerContext.newPage();
    await login(partnerPage, 'jamie@example.com', 'password');
    await expect(partnerPage.getByRole('heading', { name: 'Your couple space is active' })).toBeVisible();
    await respondInConversation(partnerPage, sessionUrl, 'What I hear is that the slower start helped you feel steadier.');

    await page.goto(sessionUrl);
    await page.getByRole('button', { name: 'Pause' }).click();
    await expect(page.getByRole('heading', { name: 'Conversation paused' })).toBeVisible();
    await page.getByRole('button', { name: 'Resume conversation' }).nth(1).click();

    await respondInConversation(page, sessionUrl, 'I want more quiet time before dinner next week.');
    await respondInConversation(partnerPage, sessionUrl, 'I can support that by handling cleanup first.');
    await respondInConversation(page, sessionUrl, 'I appreciate how you stay calm when things get busy.');
    await respondInConversation(partnerPage, sessionUrl, 'I appreciate how clearly you name what you need.');

    await expect(partnerPage.getByRole('heading', { name: 'Conversation complete' })).toBeVisible();

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
