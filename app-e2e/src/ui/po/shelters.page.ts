import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class SheltersPage extends MainPage {
    readonly heading: Locator;

    readonly shelterCards: Locator;

    constructor(page: Page) {
        super(page, PATHS.shelters);
        this.heading = page.getByRole('heading', { name: HEADINGS.sheltersList });
        this.shelterCards = page.getByTestId('shelter-card');
    }
}
