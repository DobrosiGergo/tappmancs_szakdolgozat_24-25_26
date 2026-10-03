import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class ShelterSetupPage extends MainPage {
    readonly createHeading: Locator;

    readonly editHeading: Locator;

    readonly nameInput: Locator;

    readonly locationInput: Locator;

    readonly descriptionInput: Locator;

    readonly submitButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.shelterSetup);
        this.createHeading = page.getByRole('heading', { name: HEADINGS.shelterCreate });
        this.editHeading = page.getByRole('heading', { name: HEADINGS.shelterEdit });
        this.nameInput = page.getByTestId('name');
        this.locationInput = page.getByTestId('location');
        this.descriptionInput = page.getByTestId('description');
        this.submitButton = page.getByTestId('shelter-submit');
    }
}
