import { Page, expect } from '@playwright/test';
import { env } from '../../config/env';
import { FLASH_MESSAGES } from '../../utils/messages';
import { ROUTES } from '../../utils/routes';
import { DashboardPage } from '../po/dashboard.page';
import { BaseSteps } from './base.steps';
import { CommonSteps } from './common.steps';

export class DashboardSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly dashboardPage: DashboardPage,
        private readonly commonSteps: CommonSteps
    ) {
        super(page);
    }

    async expectGreeting(userName: string = env.user.name): Promise<void> {
        await expect(this.dashboardPage.heroTitle).toBeVisible();
        await expect(this.dashboardPage.userName).toHaveText(userName);
    }

    async expectRoleBadge(label: string): Promise<void> {
        await expect(this.dashboardPage.roleBadge).toHaveText(label);
    }

    async goToPets(): Promise<void> {
        await this.dashboardPage.browsePetsLink.click();
        await expect(this.page).toHaveURL(ROUTES.pets);
    }

    async leaveShelter(): Promise<void> {
        await this.dashboardPage.goto();
        this.commonSteps.acceptNextDialog();
        await this.dashboardPage.leaveShelterButton.click();
        await this.commonSteps.expectFlashMessage(FLASH_MESSAGES.shelterLeft);
    }
}
