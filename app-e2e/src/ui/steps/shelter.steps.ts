import { Page, expect } from '@playwright/test';
import { E2E_ENTITY_PREFIX } from '../../utils/form-texts';
import { FLASH_MESSAGES } from '../../utils/messages';
import { ROUTES } from '../../utils/routes';
import { ShelterCardComponent } from '../po/components/shelter-card.component';
import { ShelterDetailPage } from '../po/shelter-detail.page';
import { ShelterSetupPage } from '../po/shelter-setup.page';
import { SheltersPage } from '../po/shelters.page';
import { BaseSteps } from './base.steps';
import { CommonSteps } from './common.steps';

export class ShelterSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly shelterDetailPage: ShelterDetailPage,
        private readonly shelterSetupPage: ShelterSetupPage,
        private readonly sheltersPage: SheltersPage,
        private readonly commonSteps: CommonSteps
    ) {
        super(page);
    }

    async createShelter(name: string, location: string, description: string): Promise<void> {
        await expect(this.shelterSetupPage.createHeading).toBeVisible();
        await this.fillShelterForm(name, location, description);
        await this.shelterSetupPage.submitButton.click();
        await expect(this.page).toHaveURL(ROUTES.dashboard);
        await this.commonSteps.expectFlashMessage(FLASH_MESSAGES.shelterCreated);
    }

    async editShelterFromNavbar(name: string, location: string, description: string): Promise<void> {
        const { navbar } = this.basePage;
        await this.clickInNavbarMenu(navbar.myShelterMenuItem, navbar.editShelterLink);
        await expect(this.shelterSetupPage.editHeading).toBeVisible();
        await this.fillShelterForm(name, location, description);
        await this.shelterSetupPage.submitButton.click();
        await expect(this.page).toHaveURL(ROUTES.shelterDetail);
        await this.commonSteps.expectFlashMessage(FLASH_MESSAGES.shelterUpdated);
        await expect(this.shelterDetailPage.shelterName).toHaveText(name);
    }

    async openSheltersList(): Promise<void> {
        await this.sheltersPage.goto();
        await this.expectSheltersListLoaded();
    }

    async expectSheltersListLoaded(): Promise<void> {
        await expect(this.sheltersPage.heading).toBeVisible();
        await expect(this.sheltersPage.shelterCards.first()).toBeVisible();
    }

    async openFirstShelterAndExpectDetails(): Promise<string> {
        const card = new ShelterCardComponent(this.sheltersPage.shelterCards.filter({ hasNotText: E2E_ENTITY_PREFIX }).first());
        const shelterName = (await card.name.innerText()).trim();
        await card.locator.click();
        await expect(this.page).toHaveURL(ROUTES.shelterDetail);
        await this.expectShelterPageLoaded();
        return shelterName;
    }

    async expectShelterPageLoaded(): Promise<void> {
        await expect(this.shelterDetailPage.shelterName).toBeVisible();
        await expect(this.shelterDetailPage.aboutHeading).toBeVisible();
    }

    private async fillShelterForm(name: string, location: string, description: string): Promise<void> {
        await this.shelterSetupPage.nameInput.fill(name);
        await this.shelterSetupPage.locationInput.fill(location);
        await this.shelterSetupPage.descriptionInput.fill(description);
    }
}
