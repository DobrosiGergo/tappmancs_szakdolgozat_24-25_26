import { expect } from '@playwright/test';
import { BaseSteps } from './base.steps';

export class CommonSteps extends BaseSteps {
    async expectFlashMessage(message: string): Promise<void> {
        await expect(this.basePage.flashMessage.locator.filter({ hasText: message })).toBeVisible();
    }

    async expectValidationError(message: string): Promise<void> {
        await expect(this.basePage.inputError.locator.filter({ hasText: message })).toBeVisible();
    }

    acceptNextDialog(): void {
        this.page.once('dialog', dialog => dialog.accept());
    }
}
