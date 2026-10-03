import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class StaffingPage extends MainPage {
    readonly heading: Locator;

    readonly emailInput: Locator;

    readonly addButton: Locator;

    readonly workerRows: Locator;

    constructor(page: Page) {
        super(page, PATHS.shelterStaffing);
        this.heading = page.getByRole('heading', { name: HEADINGS.staffing });
        this.emailInput = page.getByTestId('staffing-email');
        this.addButton = page.getByTestId('staffing-add-button');
        this.workerRows = page.getByTestId('worker-row');
    }
}
