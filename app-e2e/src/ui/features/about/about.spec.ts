import { test } from '../../../fixtures/test';

test.describe('About page @ui @smoke @about', () => {
    test('should render every section of the about page', async ({ aboutSteps }) => {
        await aboutSteps.openAboutPage();
    });

    test('should reach the pet list from the about page CTA', async ({ aboutSteps, petsSteps }) => {
        await aboutSteps.openAboutPage();
        await aboutSteps.openPetsViaCta();
        await petsSteps.expectPetsListLoaded();
    });

    test('should reach the shelter list from the about page CTA', async ({ aboutSteps, shelterSteps }) => {
        await aboutSteps.openAboutPage();
        await aboutSteps.openSheltersViaCta();
        await shelterSteps.expectSheltersListLoaded();
    });
});
