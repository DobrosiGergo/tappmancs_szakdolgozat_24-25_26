import { Page, expect } from '@playwright/test';
import { APP_NAME } from '../../utils/labels';
import { ROUTES } from '../../utils/routes';
import { HomePage } from '../po/home.page';
import { BaseSteps } from './base.steps';

export class HomeSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly homePage: HomePage
    ) {
        super(page);
    }

    async openHomePage(): Promise<void> {
        await this.homePage.goto();
        await expect(this.page).toHaveTitle(new RegExp(APP_NAME));
        await expect(this.homePage.appTitle).toHaveText(APP_NAME);
        await expect(this.homePage.appSubtitle).toBeVisible();
        await expect(this.homePage.appDescription).toBeVisible();
        await expect(this.homePage.learnMoreButton).toBeVisible();
    }

    async openAboutViaCta(): Promise<void> {
        await this.homePage.learnMoreButton.click();
        await expect(this.page).toHaveURL(ROUTES.about);
    }

    async openPetsViaNavbar(): Promise<void> {
        await this.basePage.navbar.petsMenuItem.click();
        await expect(this.page).toHaveURL(ROUTES.pets);
    }

    async openSheltersViaNavbar(): Promise<void> {
        await this.basePage.navbar.sheltersMenuItem.click();
        await expect(this.page).toHaveURL(ROUTES.shelters);
    }

    async expectBackOnHomePage(): Promise<void> {
        await expect(this.homePage.appTitle).toHaveText(APP_NAME);
    }
}
