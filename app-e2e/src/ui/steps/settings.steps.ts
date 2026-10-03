import { Page, expect } from '@playwright/test';
import { env } from '../../config/env';
import { homeUrl } from '../../utils/routes';
import { SettingsDeletePage } from '../po/settings-delete.page';
import { SettingsPage } from '../po/settings.page';
import { BaseSteps } from './base.steps';

export class SettingsSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly settingsPage: SettingsPage,
        private readonly settingsDeletePage: SettingsDeletePage
    ) {
        super(page);
    }

    async expectSettingsIndex(): Promise<void> {
        await this.settingsPage.goto();
        await expect(this.settingsPage.heading).toBeVisible();
        await expect(this.settingsPage.profileCard).toBeVisible();
        await expect(this.settingsPage.passwordCard).toBeVisible();
        await expect(this.settingsPage.deleteCard).toBeVisible();
    }

    async expectProfileForm(expectedName: string, expectedEmail: string): Promise<void> {
        await this.settingsPage.goto();
        await this.settingsPage.profileCard.click();
        await expect(this.settingsPage.profileHeading).toBeVisible();
        await expect(this.settingsPage.profileNameInput).toHaveValue(expectedName);
        await expect(this.settingsPage.profileEmailInput).toHaveValue(expectedEmail);
        await expect(this.settingsPage.saveButton).toBeVisible();
    }

    async expectPasswordForm(): Promise<void> {
        await this.settingsPage.goto();
        await this.settingsPage.passwordCard.click();
        await expect(this.settingsPage.passwordHeading).toBeVisible();
        await expect(this.settingsPage.currentPasswordInput).toBeVisible();
        await expect(this.settingsPage.newPasswordInput).toBeVisible();
        await expect(this.settingsPage.newPasswordConfirmInput).toBeVisible();
        await expect(this.settingsPage.saveButton).toBeVisible();
    }

    async expectDeletePage(): Promise<void> {
        await this.settingsPage.goto();
        await this.settingsPage.deleteCard.click();
        await expect(this.settingsDeletePage.heading.first()).toBeVisible();
        await expect(this.settingsDeletePage.startButton).toBeVisible();
    }

    async deleteAccount(password: string): Promise<void> {
        await this.settingsDeletePage.goto();
        await this.settingsDeletePage.startButton.click();
        await this.settingsDeletePage.passwordInput.fill(password);
        await this.settingsDeletePage.confirmButton.click();
        await expect(this.page).toHaveURL(homeUrl(env.baseUrl));
    }
}
