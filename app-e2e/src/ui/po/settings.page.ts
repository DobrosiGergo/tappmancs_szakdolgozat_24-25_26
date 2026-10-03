import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class SettingsPage extends MainPage {
    readonly heading: Locator;

    readonly profileCard: Locator;

    readonly passwordCard: Locator;

    readonly deleteCard: Locator;

    readonly profileHeading: Locator;

    readonly profileNameInput: Locator;

    readonly profileEmailInput: Locator;

    readonly passwordHeading: Locator;

    readonly currentPasswordInput: Locator;

    readonly newPasswordInput: Locator;

    readonly newPasswordConfirmInput: Locator;

    readonly saveButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.settings);
        this.heading = page.getByRole('heading', { name: HEADINGS.settings });
        this.profileCard = page.getByTestId('settings-profile-card');
        this.passwordCard = page.getByTestId('settings-password-card');
        this.deleteCard = page.getByTestId('settings-delete-card');
        this.profileHeading = page.getByRole('heading', { name: HEADINGS.profileEdit });
        this.profileNameInput = page.getByTestId('profile-name');
        this.profileEmailInput = page.getByTestId('profile-email');
        this.passwordHeading = page.getByRole('heading', { name: HEADINGS.passwordEdit });
        this.currentPasswordInput = page.getByTestId('password-current');
        this.newPasswordInput = page.getByTestId('password-new');
        this.newPasswordConfirmInput = page.getByTestId('password-new-confirm');
        this.saveButton = page.getByTestId('settings-save');
    }
}
