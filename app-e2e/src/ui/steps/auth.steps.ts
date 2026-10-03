import { Page, expect } from '@playwright/test';
import { env } from '../../config/env';
import { APP_NAME } from '../../utils/labels';
import { VALIDATION_MESSAGES } from '../../utils/messages';
import { ROUTES, homeUrl } from '../../utils/routes';
import { HomePage } from '../po/home.page';
import { LoginPage } from '../po/login.page';
import { BaseSteps } from './base.steps';
import { CommonSteps } from './common.steps';

export class AuthSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly loginPage: LoginPage,
        private readonly homePage: HomePage,
        private readonly commonSteps: CommonSteps
    ) {
        super(page);
    }

    async login(email: string = env.user.email, password: string = env.user.password): Promise<void> {
        await this.loginPage.goto();
        await expect(this.loginPage.heading).toBeVisible();
        await this.submitLoginForm(email, password);
        await expect(this.page).toHaveURL(ROUTES.dashboard);
    }

    async loginExpectingError(email: string, password: string): Promise<void> {
        await this.loginPage.goto();
        await this.submitLoginForm(email, password);
        await this.commonSteps.expectValidationError(VALIDATION_MESSAGES.loginFailed);
        await expect(this.page).toHaveURL(ROUTES.login);
    }

    async logout(): Promise<void> {
        const { navbar } = this.basePage;
        await this.clickInNavbarMenu(navbar.accountMenuItem, navbar.logoutButton);
        await expect(this.page).toHaveURL(homeUrl(env.baseUrl));
        await expect(this.homePage.appTitle).toHaveText(APP_NAME);
    }

    private async submitLoginForm(email: string, password: string): Promise<void> {
        await this.loginPage.emailInput.fill(email);
        await this.loginPage.passwordInput.fill(password);
        await this.loginPage.submitButton.click();
    }
}
