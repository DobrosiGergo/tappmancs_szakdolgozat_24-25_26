import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class ShelterRolePage extends MainPage {
    readonly heading: Locator;

    readonly workerButton: Locator;

    readonly ownerButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.registerShelterRole);
        this.heading = page.getByRole('heading', { name: HEADINGS.shelterRoleInfo });
        this.workerButton = page.getByTestId('role-shelterWorker');
        this.ownerButton = page.getByTestId('role-shelterOwner');
    }
}
