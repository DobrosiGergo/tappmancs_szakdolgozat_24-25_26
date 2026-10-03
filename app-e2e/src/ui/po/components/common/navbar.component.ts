import { Locator } from '@playwright/test';
import { Component } from './component';

export class NavbarComponent extends Component {
    readonly accountMenuItem: Locator;

    readonly petsMenuItem: Locator;

    readonly sheltersMenuItem: Locator;

    readonly myShelterMenuItem: Locator;

    readonly workerShelterMenuItem: Locator;

    readonly staffMenuItem: Locator;

    readonly loginLink: Locator;

    readonly logoutButton: Locator;

    readonly editShelterLink: Locator;

    readonly manageStaffLink: Locator;

    readonly newPetLink: Locator;

    readonly myPetsLink: Locator;

    constructor(locator: Locator) {
        super(locator);
        this.accountMenuItem = locator.getByTestId('nav-account');
        this.petsMenuItem = locator.getByTestId('nav-pets');
        this.sheltersMenuItem = locator.getByTestId('nav-shelters');
        this.myShelterMenuItem = locator.getByTestId('nav-my-shelter');
        this.workerShelterMenuItem = locator.getByTestId('nav-worker-shelter');
        this.staffMenuItem = locator.getByTestId('nav-staff');
        this.loginLink = locator.getByTestId('nav-login');
        this.logoutButton = locator.getByTestId('nav-logout');
        this.editShelterLink = locator.getByTestId('nav-edit-shelter');
        this.manageStaffLink = locator.getByTestId('nav-manage-staff');
        this.newPetLink = locator.getByTestId('nav-new-pet');
        this.myPetsLink = locator.getByTestId('nav-my-pets');
    }
}
