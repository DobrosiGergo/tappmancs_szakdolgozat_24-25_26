import { test } from '../../../fixtures/test';

test.describe('Home page @ui @smoke @home', () => {
    test('should render every element of the hero section', async ({ homeSteps }) => {
        await homeSteps.openHomePage();
    });

    test('should navigate to the about page from the CTA button', async ({ homeSteps }) => {
        await homeSteps.openHomePage();
        await homeSteps.openAboutViaCta();
    });

    test('should let a guest reach the pet list from the navbar', async ({ homeSteps, petsSteps }) => {
        await homeSteps.openHomePage();
        await homeSteps.openPetsViaNavbar();
        await petsSteps.expectPetsListLoaded();
    });

    test('should let a guest reach the shelter list from the navbar', async ({ homeSteps, shelterSteps }) => {
        await homeSteps.openHomePage();
        await homeSteps.openSheltersViaNavbar();
        await shelterSteps.expectSheltersListLoaded();
    });
});
