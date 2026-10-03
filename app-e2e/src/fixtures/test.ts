import { test as base } from '@playwright/test';
import { AboutPage } from '../ui/po/about.page';
import { DashboardPage } from '../ui/po/dashboard.page';
import { HomePage } from '../ui/po/home.page';
import { LoginPage } from '../ui/po/login.page';
import { PetDetailPage } from '../ui/po/pet-detail.page';
import { PetFormPage } from '../ui/po/pet-form.page';
import { PetsManagePage } from '../ui/po/pets-manage.page';
import { PetsPage } from '../ui/po/pets.page';
import { RegisterPage } from '../ui/po/register.page';
import { RoleSelectionPage } from '../ui/po/role-selection.page';
import { SettingsDeletePage } from '../ui/po/settings-delete.page';
import { SettingsPage } from '../ui/po/settings.page';
import { ShelterDetailPage } from '../ui/po/shelter-detail.page';
import { ShelterRolePage } from '../ui/po/shelter-role.page';
import { ShelterSetupPage } from '../ui/po/shelter-setup.page';
import { SheltersPage } from '../ui/po/shelters.page';
import { StaffingPage } from '../ui/po/staffing.page';
import { AboutSteps } from '../ui/steps/about.steps';
import { AuthSteps } from '../ui/steps/auth.steps';
import { CommonSteps } from '../ui/steps/common.steps';
import { DashboardSteps } from '../ui/steps/dashboard.steps';
import { HomeSteps } from '../ui/steps/home.steps';
import { PetManagementSteps } from '../ui/steps/pet-management.steps';
import { PetsSteps } from '../ui/steps/pets.steps';
import { RegistrationSteps } from '../ui/steps/registration.steps';
import { SettingsSteps } from '../ui/steps/settings.steps';
import { ShelterSteps } from '../ui/steps/shelter.steps';
import { StaffingSteps } from '../ui/steps/staffing.steps';

/**
 * Specs only ever ask for step fixtures; the page objects they need are built here.
 * Add a new fixture when a spec needs a new set of steps.
 */
type StepFixtures = {
    commonSteps: CommonSteps;
    aboutSteps: AboutSteps;
    homeSteps: HomeSteps;
    authSteps: AuthSteps;
    dashboardSteps: DashboardSteps;
    petsSteps: PetsSteps;
    petManagementSteps: PetManagementSteps;
    registrationSteps: RegistrationSteps;
    settingsSteps: SettingsSteps;
    shelterSteps: ShelterSteps;
    staffingSteps: StaffingSteps;
};

export const test = base.extend<StepFixtures>({
    commonSteps: async ({ page }, use) => {
        await use(new CommonSteps(page));
    },
    aboutSteps: async ({ page }, use) => {
        await use(new AboutSteps(page, new AboutPage(page)));
    },
    homeSteps: async ({ page }, use) => {
        await use(new HomeSteps(page, new HomePage(page)));
    },
    authSteps: async ({ page, commonSteps }, use) => {
        await use(new AuthSteps(page, new LoginPage(page), new HomePage(page), commonSteps));
    },
    dashboardSteps: async ({ page, commonSteps }, use) => {
        await use(new DashboardSteps(page, new DashboardPage(page), commonSteps));
    },
    petsSteps: async ({ page }, use) => {
        await use(new PetsSteps(page, new PetsPage(page), new PetDetailPage(page)));
    },
    petManagementSteps: async ({ page, commonSteps }, use) => {
        await use(new PetManagementSteps(page, new PetFormPage(page), new PetsManagePage(page), new PetDetailPage(page), commonSteps));
    },
    registrationSteps: async ({ page }, use) => {
        await use(new RegistrationSteps(page, new RoleSelectionPage(page), new ShelterRolePage(page), new RegisterPage(page), new ShelterSetupPage(page)));
    },
    settingsSteps: async ({ page }, use) => {
        await use(new SettingsSteps(page, new SettingsPage(page), new SettingsDeletePage(page)));
    },
    shelterSteps: async ({ page, commonSteps }, use) => {
        await use(new ShelterSteps(page, new ShelterDetailPage(page), new ShelterSetupPage(page), new SheltersPage(page), commonSteps));
    },
    staffingSteps: async ({ page, commonSteps }, use) => {
        await use(new StaffingSteps(page, new StaffingPage(page), commonSteps));
    },
});

export { expect } from '@playwright/test';
