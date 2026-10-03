import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class PetsPage extends MainPage {
    readonly heading: Locator;

    readonly petCards: Locator;

    constructor(page: Page) {
        super(page, PATHS.pets);
        this.heading = page.getByRole('heading', { name: HEADINGS.petsList });
        this.petCards = page.getByTestId('pet-card');
    }
}
