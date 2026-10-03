import { Locator } from '@playwright/test';
import { Component } from './common/component';

const TRIGGER_INDEX = 0;
const FIRST_OPTION_INDEX = TRIGGER_INDEX + 1;

export class DropdownComponent extends Component {
    readonly trigger: Locator;

    readonly firstOption: Locator;

    constructor(locator: Locator) {
        super(locator);
        this.trigger = locator.getByRole('button').nth(TRIGGER_INDEX);
        this.firstOption = locator.getByRole('button').nth(FIRST_OPTION_INDEX);
    }
}
