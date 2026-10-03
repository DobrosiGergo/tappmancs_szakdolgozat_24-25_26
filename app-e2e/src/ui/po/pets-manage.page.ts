import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class PetsManagePage extends MainPage {
    readonly heading: Locator;

    readonly petItems: Locator;

    constructor(page: Page) {
        super(page, PATHS.petsManage);
        this.heading = page.getByRole('heading', { name: HEADINGS.petsManage });
        this.petItems = page.getByTestId('pet-manage-item');
    }
}
