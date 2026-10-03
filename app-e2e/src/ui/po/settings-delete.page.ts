import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class SettingsDeletePage extends MainPage {
    readonly heading: Locator;

    readonly startButton: Locator;

    readonly passwordInput: Locator;

    readonly confirmButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.settingsDelete);
        this.heading = page.getByRole('heading', { name: HEADINGS.accountDelete });
        this.startButton = page.getByTestId('delete-account-start');
        this.passwordInput = page.getByTestId('delete-account-password');
        this.confirmButton = page.getByTestId('delete-account-confirm');
    }
}
