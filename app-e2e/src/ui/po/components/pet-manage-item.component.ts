import { Locator } from '@playwright/test';
import { Component } from './common/component';
import { PetCardComponent } from './common/pet-card.component';

export class PetManageItemComponent extends Component {
    readonly card: PetCardComponent;

    readonly editLink: Locator;

    readonly deleteButton: Locator;

    readonly deleteConfirmButton: Locator;

    constructor(locator: Locator) {
        super(locator);
        this.card = new PetCardComponent(locator.getByTestId('pet-card'));
        this.editLink = locator.getByTestId('pet-edit-link');
        this.deleteButton = locator.getByTestId('pet-delete-button');
        this.deleteConfirmButton = locator.getByTestId('pet-delete-confirm');
    }
}
