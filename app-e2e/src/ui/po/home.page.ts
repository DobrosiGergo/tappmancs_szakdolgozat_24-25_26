import { Locator, Page } from '@playwright/test';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class HomePage extends MainPage {
    readonly appTitle: Locator;

    readonly appSubtitle: Locator;

    readonly appDescription: Locator;

    readonly learnMoreButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.home);
        this.appTitle = page.getByTestId('app-title');
        this.appSubtitle = page.getByTestId('app-subtitle');
        this.appDescription = page.getByTestId('app-description');
        this.learnMoreButton = page.getByTestId('learn-more-button');
    }
}
