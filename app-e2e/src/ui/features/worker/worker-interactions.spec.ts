import { env } from '../../../config/env';
import { test } from '../../../fixtures/test';
import { PET_TEXTS, uniqueName } from '../../../utils/form-texts';
import { PET_STATUSES, ROLE_LABELS } from '../../../utils/labels';

test.describe('Shelter worker interactions @ui @worker @mutating', () => {
    test('should let an employed worker manage pets and then leave the shelter', async ({ authSteps, staffingSteps, dashboardSteps, petManagementSteps }) => {
        const petName = uniqueName('Pet');
        const renamedPetName = `${petName} Renamed`;

        await authSteps.login(env.shelterOwner.email, env.shelterOwner.password);
        await staffingSteps.openStaffingFromNavbar();
        await staffingSteps.ensureWorkerEmployed(env.shelterWorker.email);
        await authSteps.logout();

        await authSteps.login(env.shelterWorker.email, env.shelterWorker.password);
        await dashboardSteps.expectGreeting(env.shelterWorker.name);
        await dashboardSteps.expectRoleBadge(ROLE_LABELS.shelterWorker);

        await petManagementSteps.createPet(petName, PET_TEXTS.description);
        await petManagementSteps.expectPetInMyPets(petName);
        await petManagementSteps.renamePet(petName, renamedPetName);
        await petManagementSteps.changePetStatus(renamedPetName, PET_STATUSES.reserved);
        await petManagementSteps.deletePet(renamedPetName);

        await dashboardSteps.leaveShelter();
    });
});
