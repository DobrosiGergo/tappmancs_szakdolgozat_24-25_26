import { Locator, Page, expect } from '@playwright/test';
import { BasePage } from '../po/components/base.page';

const DROPDOWN_ITEM_CLICK_TIMEOUT_MS = 2000;

export abstract class BaseSteps {
    protected readonly page: Page;

    protected readonly basePage: BasePage;

    constructor(page: Page) {
        this.page = page;
        this.basePage = new BasePage(page);
    }

    protected async clickInNavbarMenu(menuItem: Locator, dropdownItem: Locator): Promise<void> {
        await expect(async () => {
            await this.page.mouse.move(0, 0);
            await menuItem.hover();
            await dropdownItem.click({ timeout: DROPDOWN_ITEM_CLICK_TIMEOUT_MS });
        }).toPass();
    }
}
