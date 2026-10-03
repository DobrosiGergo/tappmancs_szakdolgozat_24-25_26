import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class ShelterDetailPage extends MainPage {
    readonly shelterName: Locator;

    readonly aboutHeading: Locator;

    constructor(page: Page) {
        super(page, PATHS.shelters);
        this.shelterName = page.getByRole('heading', { level: 1 });
        this.aboutHeading = page.getByRole('heading', { name: HEADINGS.shelterAbout });
    }
}
