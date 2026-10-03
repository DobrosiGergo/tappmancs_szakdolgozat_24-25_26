import { Page, expect } from '@playwright/test';
import { E2E_ENTITY_PREFIX } from '../../utils/form-texts';
import { ROUTES } from '../../utils/routes';
import { PetCardComponent } from '../po/components/common/pet-card.component';
import { PetDetailPage } from '../po/pet-detail.page';
import { PetsPage } from '../po/pets.page';
import { BaseSteps } from './base.steps';

export class PetsSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly petsPage: PetsPage,
        private readonly petDetailPage: PetDetailPage
    ) {
        super(page);
    }

    async openPetsList(): Promise<void> {
        await this.petsPage.goto();
        await this.expectPetsListLoaded();
    }

    async expectPetsListLoaded(): Promise<void> {
        await expect(this.petsPage.heading).toBeVisible();
        await expect(this.petsPage.petCards.first()).toBeVisible();
    }

    async openPetAndExpectDetails(): Promise<string> {
        const card = this.seededPetCard();
        const petName = (await card.name.innerText()).trim();
        await card.locator.click();
        await expect(this.page).toHaveURL(ROUTES.petDetail);
        await expect(this.petDetailPage.petName).toHaveText(petName);
        await expect(this.petDetailPage.basicDataHeading).toBeVisible();
        await expect(this.petDetailPage.adoptionInterestHeading).toBeVisible();
        await expect(this.petDetailPage.contactHeading).toBeVisible();
        return petName;
    }

    async openShelterFromPet(): Promise<void> {
        await this.petDetailPage.viewShelterLink.click();
        await expect(this.page).toHaveURL(ROUTES.shelterDetail);
    }

    async sendContactMessage(message: string): Promise<void> {
        await this.petDetailPage.contactHeading.scrollIntoViewIfNeeded();
        await this.petDetailPage.messageTextarea.fill(message);
        await this.petDetailPage.sendMessageButton.click();
        await expect(this.petDetailPage.messageAlreadySentNotice).toBeVisible();
    }

    private seededPetCard(): PetCardComponent {
        return new PetCardComponent(this.petsPage.petCards.filter({ hasNotText: E2E_ENTITY_PREFIX }).first());
    }
}
