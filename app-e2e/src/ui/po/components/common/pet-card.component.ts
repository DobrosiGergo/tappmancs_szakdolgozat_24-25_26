import { Locator } from '@playwright/test';
import { Component } from './component';

export class PetCardComponent extends Component {
    readonly name: Locator;

    constructor(locator: Locator) {
        super(locator);
        this.name = locator.getByTestId('pet-card-name');
    }
}
