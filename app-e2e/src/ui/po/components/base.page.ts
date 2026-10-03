import { Page } from '@playwright/test';
import { Component } from './common/component';
import { NavbarComponent } from './common/navbar.component';

export class BasePage {
    readonly page: Page;

    readonly navbar: NavbarComponent;

    readonly flashMessage: Component;

    readonly inputError: Component;

    constructor(page: Page) {
        this.page = page;
        this.navbar = new NavbarComponent(page.locator('header').first());
        this.flashMessage = new Component(page.getByTestId('flash-message'));
        this.inputError = new Component(page.getByTestId('input-error'));
    }
}
