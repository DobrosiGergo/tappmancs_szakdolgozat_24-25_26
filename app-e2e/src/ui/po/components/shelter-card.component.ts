import { Locator } from '@playwright/test';
import { Component } from './common/component';

export class ShelterCardComponent extends Component {
    readonly name: Locator;

    constructor(locator: Locator) {
        super(locator);
        this.name = locator.getByTestId('shelter-card-name');
    }
}
