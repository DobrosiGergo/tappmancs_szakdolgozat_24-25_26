import { Locator } from '@playwright/test';

export class Component {
    readonly locator: Locator;

    constructor(locator: Locator) {
        this.locator = locator;
    }
}
