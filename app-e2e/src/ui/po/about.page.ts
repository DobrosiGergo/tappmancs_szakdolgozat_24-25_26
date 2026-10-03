import { Locator, Page } from '@playwright/test';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class AboutPage extends MainPage {
    readonly title: Locator;

    readonly petsLink: Locator;

    readonly sheltersLink: Locator;

    readonly featuresSection: Locator;

    readonly rolesSection: Locator;

    readonly projectSection: Locator;

    readonly e2eSection: Locator;

    constructor(page: Page) {
        super(page, PATHS.about);
        this.title = page.getByTestId('about-title');
        this.petsLink = page.getByTestId('about-pets-link');
        this.sheltersLink = page.getByTestId('about-shelters-link');
        this.featuresSection = page.getByTestId('about-features');
        this.rolesSection = page.getByTestId('about-roles');
        this.projectSection = page.getByTestId('about-project');
        this.e2eSection = page.getByTestId('about-e2e');
    }
}
