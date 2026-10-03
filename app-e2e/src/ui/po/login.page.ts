import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class LoginPage extends MainPage {
    readonly heading: Locator;

    readonly emailInput: Locator;

    readonly passwordInput: Locator;

    readonly submitButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.login);
        this.heading = page.getByRole('heading', { name: HEADINGS.login });
        this.emailInput = page.getByTestId('email');
        this.passwordInput = page.getByTestId('password');
        this.submitButton = page.getByTestId('login-submit');
    }
}
