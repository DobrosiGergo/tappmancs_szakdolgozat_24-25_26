import { Locator, Page, expect } from '@playwright/test';
import { PetStatus } from '../../utils/labels';
import { FLASH_MESSAGES } from '../../utils/messages';
import { ROUTES } from '../../utils/routes';
import { DropdownComponent } from '../po/components/dropdown.component';
import { PetManageItemComponent } from '../po/components/pet-manage-item.component';
import { PetDetailPage } from '../po/pet-detail.page';
import { PetFormPage } from '../po/pet-form.page';
import { PetsManagePage } from '../po/pets-manage.page';
import { BaseSteps } from './base.steps';
import { CommonSteps } from './common.steps';

export class PetManagementSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly petFormPage: PetFormPage,
        private readonly petsManagePage: PetsManagePage,
        private readonly petDetailPage: PetDetailPage,
        private readonly commonSteps: CommonSteps
    ) {
        super(page);
    }

    async createPet(name: string, description: string): Promise<void> {
        const { navbar } = this.basePage;
        await this.clickInNavbarMenu(navbar.petsMenuItem, navbar.newPetLink);
        await expect(this.petFormPage.createHeading).toBeVisible();
        await this.petFormPage.nameInput.fill(name);
        await this.selectFirstOption(this.petFormPage.speciesDropdown);
        await this.selectFirstOption(this.petFormPage.breedDropdown);
        await this.petFormPage.descriptionInput.fill(description);
        await this.petFormPage.submitButton.click();
        await expect(this.page).toHaveURL(ROUTES.petDetail);
        await this.commonSteps.expectFlashMessage(FLASH_MESSAGES.petCreated);
        await expect(this.petDetailPage.petName).toHaveText(name);
    }

    async expectPetInMyPets(name: string): Promise<void> {
        const { navbar } = this.basePage;
        await this.clickInNavbarMenu(navbar.petsMenuItem, navbar.myPetsLink);
        await expect(this.petsManagePage.heading).toBeVisible();
        await expect(this.petItem(name).locator).toBeVisible();
    }

    async renamePet(currentName: string, newName: string): Promise<void> {
        await this.petItem(currentName).editLink.click();
        await expect(this.petFormPage.editHeading).toBeVisible();
        await this.petFormPage.nameInput.fill(newName);
        await this.petFormPage.submitButton.click();
        await expect(this.page).toHaveURL(ROUTES.petsManage);
        await this.commonSteps.expectFlashMessage(FLASH_MESSAGES.petUpdated);
        await expect(this.petItem(newName).locator).toBeVisible();
    }

    async changePetStatus(name: string, status: PetStatus): Promise<void> {
        await this.petItem(name).card.locator.click();
        await expect(this.petDetailPage.petName).toHaveText(name);
        await expect(this.petDetailPage.statusHeading).toBeVisible();
        await this.statusButton(status).click();
        await this.commonSteps.expectFlashMessage(FLASH_MESSAGES.petStatusUpdated);
    }

    async deletePet(name: string): Promise<void> {
        await this.petsManagePage.goto();
        await this.petItem(name).deleteButton.click();
        await this.petItem(name).deleteConfirmButton.click();
        await this.commonSteps.expectFlashMessage(FLASH_MESSAGES.petDeleted);
        await expect(this.petItem(name).locator).toBeHidden();
    }

    private petItem(name: string): PetManageItemComponent {
        return new PetManageItemComponent(this.petsManagePage.petItems.filter({ hasText: name }));
    }

    private statusButton(status: PetStatus): Locator {
        return {
            free: this.petDetailPage.freeStatusButton,
            reserved: this.petDetailPage.reservedStatusButton,
            adopted: this.petDetailPage.adoptedStatusButton,
        }[status];
    }

    private async selectFirstOption(dropdown: DropdownComponent): Promise<void> {
        await dropdown.trigger.click();
        await dropdown.firstOption.click();
    }
}
