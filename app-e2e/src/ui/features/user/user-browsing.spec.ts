import { test } from '../../../fixtures/test';
import { CONTACT_MESSAGE } from '../../../utils/form-texts';
import { uniqueUser } from '../../../utils/users';

test.describe('User browsing @ui @user', () => {
    test('should let a logged-in user browse the shelters', async ({ authSteps, shelterSteps }) => {
        await authSteps.login();
        await shelterSteps.openSheltersList();
        await shelterSteps.openFirstShelterAndExpectDetails();
    });

    test('should let a logged-in user browse the pets', async ({ authSteps, dashboardSteps, petsSteps, shelterSteps }) => {
        await authSteps.login();
        await dashboardSteps.goToPets();
        await petsSteps.expectPetsListLoaded();
        await petsSteps.openPetAndExpectDetails();
        await petsSteps.openShelterFromPet();
        await shelterSteps.expectShelterPageLoaded();
    });

    test("should send a message to a pet's shelter @mutating", async ({ registrationSteps, petsSteps, settingsSteps }) => {
        const sender = uniqueUser('sender');
        await registrationSteps.registerAdopter(sender);
        await petsSteps.openPetsList();
        await petsSteps.openPetAndExpectDetails();
        await petsSteps.sendContactMessage(CONTACT_MESSAGE);
        await settingsSteps.deleteAccount(sender.password);
    });
});
