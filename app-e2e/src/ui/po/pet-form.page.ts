import { Locator, Page } from '@playwright/test';
import { HEADINGS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { DropdownComponent } from './components/dropdown.component';
import { MainPage } from './components/main.page';

export class PetFormPage extends MainPage {
    readonly createHeading: Locator;

    readonly editHeading: Locator;

    readonly nameInput: Locator;

    readonly descriptionInput: Locator;

    readonly speciesDropdown: DropdownComponent;

    readonly breedDropdown: DropdownComponent;

    readonly submitButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.petCreate);
        this.createHeading = page.getByRole('heading', { name: HEADINGS.petCreate });
        this.editHeading = page.getByRole('heading', { name: HEADINGS.petEdit });
        this.nameInput = page.getByTestId('name');
        this.descriptionInput = page.getByTestId('description');
        this.speciesDropdown = new DropdownComponent(page.getByTestId('species-select'));
        this.breedDropdown = new DropdownComponent(page.getByTestId('breed-select'));
        this.submitButton = page.getByTestId('pet-submit');
    }
}
