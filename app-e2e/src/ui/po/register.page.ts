import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class RegisterPage extends MainPage {
    readonly heading: Locator;

    readonly emailInput: Locator;

    readonly nameInput: Locator;

    readonly passwordInput: Locator;

    readonly passwordConfirmInput: Locator;

    readonly phoneInput: Locator;

    readonly submitButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.register);
        this.heading = page.getByRole('heading', { name: HEADINGS.register });
        this.emailInput = page.getByTestId('register-email');
        this.nameInput = page.getByTestId('register-name');
        this.passwordInput = page.getByTestId('register-password');
        this.passwordConfirmInput = page.getByTestId('register-password-confirm');
        this.phoneInput = page.getByTestId('register-phone');
        this.submitButton = page.getByTestId('register-submit');
    }
}
