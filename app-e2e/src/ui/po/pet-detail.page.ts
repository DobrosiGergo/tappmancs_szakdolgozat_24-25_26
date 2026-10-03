import { Locator, Page } from '@playwright/test';
import { HEADINGS, LINKS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class PetDetailPage extends MainPage {
    readonly petName: Locator;

    readonly basicDataHeading: Locator;

    readonly adoptionInterestHeading: Locator;

    readonly viewShelterLink: Locator;

    readonly contactHeading: Locator;

    readonly messageTextarea: Locator;

    readonly sendMessageButton: Locator;

    readonly messageAlreadySentNotice: Locator;

    readonly statusHeading: Locator;

    readonly freeStatusButton: Locator;

    readonly reservedStatusButton: Locator;

    readonly adoptedStatusButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.pets);
        this.petName = page.getByRole('heading', { level: 1 });
        this.basicDataHeading = page.getByRole('heading', { name: HEADINGS.petBasicData });
        this.adoptionInterestHeading = page.getByRole('heading', {
            name: HEADINGS.petAdoptionInterest,
        });
        this.viewShelterLink = page.getByRole('link', { name: LINKS.viewShelter });
        this.contactHeading = page.getByRole('heading', { name: HEADINGS.contact });
        this.messageTextarea = page.getByTestId('contact-message-input');
        this.sendMessageButton = page.getByTestId('contact-send-button');
        this.messageAlreadySentNotice = page.getByTestId('contact-already-sent');
        this.statusHeading = page.getByRole('heading', { name: HEADINGS.petStatus });
        this.freeStatusButton = page.getByTestId('pet-status-free');
        this.reservedStatusButton = page.getByTestId('pet-status-reserved');
        this.adoptedStatusButton = page.getByTestId('pet-status-adopted');
    }
}
