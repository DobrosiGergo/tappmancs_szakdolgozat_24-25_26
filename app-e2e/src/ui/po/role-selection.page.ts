import { Locator, Page } from '@playwright/test';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class RoleSelectionPage extends MainPage {
    readonly adopterButton: Locator;

    readonly shelterButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.registerRole);
        this.adopterButton = page.getByTestId('role-User');
        this.shelterButton = page.getByTestId('role-shelter');
    }
}
