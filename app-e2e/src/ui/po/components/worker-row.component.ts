import { Locator } from '@playwright/test';
import { Component } from './common/component';

export class WorkerRowComponent extends Component {
    readonly removeButton: Locator;

    constructor(locator: Locator) {
        super(locator);
        this.removeButton = locator.getByTestId('worker-remove-button');
    }
}
