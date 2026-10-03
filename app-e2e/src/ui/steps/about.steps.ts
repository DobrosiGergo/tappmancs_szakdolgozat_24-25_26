import { Page, expect } from '@playwright/test';
import { APP_NAME } from '../../utils/labels';
import { ROUTES } from '../../utils/routes';
import { AboutPage } from '../po/about.page';
import { BaseSteps } from './base.steps';

export class AboutSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly aboutPage: AboutPage
    ) {
        super(page);
    }

    async openAboutPage(): Promise<void> {
        await this.aboutPage.goto();
        await this.expectAboutPageLoaded();
    }

    async expectAboutPageLoaded(): Promise<void> {
        await expect(this.aboutPage.title).toHaveText(APP_NAME);
        await expect(this.aboutPage.featuresSection).toBeVisible();
        await expect(this.aboutPage.rolesSection).toBeVisible();
        await expect(this.aboutPage.projectSection).toBeVisible();
    }

    async openPetsViaCta(): Promise<void> {
        await this.aboutPage.petsLink.click();
        await expect(this.page).toHaveURL(ROUTES.pets);
    }

    async openSheltersViaCta(): Promise<void> {
        await this.aboutPage.sheltersLink.click();
        await expect(this.page).toHaveURL(ROUTES.shelters);
    }
}
