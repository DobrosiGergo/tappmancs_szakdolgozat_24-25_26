import { Page, expect } from '@playwright/test';
import { LIVEWIRE_UPDATE_PATH, ROUTES } from '../../utils/routes';
import { NewUser } from '../../utils/users';
import { RegisterPage } from '../po/register.page';
import { RoleSelectionPage } from '../po/role-selection.page';
import { ShelterRolePage } from '../po/shelter-role.page';
import { ShelterSetupPage } from '../po/shelter-setup.page';
import { BaseSteps } from './base.steps';

export class RegistrationSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly roleSelectionPage: RoleSelectionPage,
        private readonly shelterRolePage: ShelterRolePage,
        private readonly registerPage: RegisterPage,
        private readonly shelterSetupPage: ShelterSetupPage
    ) {
        super(page);
    }

    async registerAdopter(user: NewUser): Promise<void> {
        await this.roleSelectionPage.goto();
        await this.roleSelectionPage.adopterButton.click();
        await this.completeRegistration(user);
        await expect(this.page).toHaveURL(ROUTES.dashboard);
    }

    async registerShelterWorker(user: NewUser): Promise<void> {
        await this.openShelterRoleSelection();
        await this.shelterRolePage.workerButton.click();
        await this.completeRegistration(user);
        await expect(this.page).toHaveURL(ROUTES.dashboard);
    }

    async registerShelterOwner(user: NewUser): Promise<void> {
        await this.openShelterRoleSelection();
        await this.shelterRolePage.ownerButton.click();
        await this.completeRegistration(user);
        await expect(this.shelterSetupPage.createHeading).toBeVisible();
    }

    private async openShelterRoleSelection(): Promise<void> {
        await this.roleSelectionPage.goto();
        await this.roleSelectionPage.shelterButton.click();
        await expect(this.shelterRolePage.heading).toBeVisible();
    }

    private async completeRegistration(user: NewUser): Promise<void> {
        await expect(this.registerPage.heading).toBeVisible();
        await this.waitForLivewireToBoot();

        const emailSynced = this.waitForEmailSync(user.email);
        await this.registerPage.emailInput.fill(user.email);
        await emailSynced;

        await this.registerPage.nameInput.fill(user.name);
        await this.registerPage.passwordInput.fill(user.password);
        await this.registerPage.passwordConfirmInput.fill(user.password);
        await this.registerPage.submitButton.click();
    }

    private async waitForLivewireToBoot(): Promise<void> {
        await this.page.waitForFunction(() => Boolean(window.Livewire?.all().length));
    }

    private waitForEmailSync(email: string): Promise<unknown> {
        return this.page.waitForResponse(response => response.url().includes(LIVEWIRE_UPDATE_PATH) && (response.request().postData() ?? '').includes(email));
    }
}
