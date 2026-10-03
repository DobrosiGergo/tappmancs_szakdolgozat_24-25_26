import { Page } from '@playwright/test';
import { BasePage } from './base.page';

export class MainPage extends BasePage {
    readonly url: string;

    constructor(page: Page, url: string) {
        super(page);
        this.url = url;
    }

    async goto(): Promise<void> {
        await this.page.goto(this.url);
    }
}
