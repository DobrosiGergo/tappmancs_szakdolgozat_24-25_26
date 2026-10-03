import { test } from '../../../fixtures/test';
import { ROLE_LABELS } from '../../../utils/labels';
import { uniqueUser } from '../../../utils/users';

test.describe('Registration @ui @auth @registration @mutating', () => {
    test('should create an adopter account', async ({ registrationSteps, dashboardSteps, settingsSteps }) => {
        const user = uniqueUser('adopter');
        await registrationSteps.registerAdopter(user);
        await dashboardSteps.expectGreeting(user.name);
        await dashboardSteps.expectRoleBadge(ROLE_LABELS.user);
        await settingsSteps.deleteAccount(user.password);
    });

    test('should create a shelter worker account', async ({ registrationSteps, dashboardSteps, settingsSteps }) => {
        const user = uniqueUser('worker');
        await registrationSteps.registerShelterWorker(user);
        await dashboardSteps.expectGreeting(user.name);
        await dashboardSteps.expectRoleBadge(ROLE_LABELS.shelterWorker);
        await settingsSteps.deleteAccount(user.password);
    });

    test('should open the shelter setup page after a shelter owner registration', async ({ registrationSteps, settingsSteps }) => {
        const user = uniqueUser('owner');
        await registrationSteps.registerShelterOwner(user);
        await settingsSteps.deleteAccount(user.password);
    });
});
