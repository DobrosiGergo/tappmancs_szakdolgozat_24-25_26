import { test } from '../../../fixtures/test';
import { SHELTER_TEXTS, uniqueName } from '../../../utils/form-texts';
import { ROLE_LABELS } from '../../../utils/labels';
import { USER_PREFIXES } from '../../../utils/users';

test.describe('Shelter management @ui @shelter', () => {
    test('should let an owner create a shelter and then update its details', async ({ registrationSteps, shelterSteps, dashboardSteps, createUser }) => {
        const owner = createUser(USER_PREFIXES.shelterOwner);
        const shelterName = uniqueName('Shelter');
        const updatedName = `${shelterName} Updated`;

        await registrationSteps.registerShelterOwner(owner);
        await shelterSteps.createShelter(shelterName, SHELTER_TEXTS.location, SHELTER_TEXTS.description);
        await dashboardSteps.expectGreeting(owner.name);
        await dashboardSteps.expectRoleBadge(ROLE_LABELS.shelterOwner);
        await shelterSteps.editShelterFromNavbar(updatedName, SHELTER_TEXTS.updatedLocation, SHELTER_TEXTS.updatedDescription);
    });
});
