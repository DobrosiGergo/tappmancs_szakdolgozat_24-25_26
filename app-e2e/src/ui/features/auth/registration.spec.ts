import { test } from '../../../fixtures/test';
import { ROLE_LABELS } from '../../../utils/labels';
import { USER_PREFIXES } from '../../../utils/users';

test.describe('Registration @ui @auth @registration', () => {
    test('should create an adopter account', async ({ registrationSteps, dashboardSteps, createUser }) => {
        const user = createUser(USER_PREFIXES.adopter);
        await registrationSteps.registerAdopter(user);
        await dashboardSteps.expectGreeting(user.name);
        await dashboardSteps.expectRoleBadge(ROLE_LABELS.user);
    });

    test('should create a shelter worker account', async ({ registrationSteps, dashboardSteps, createUser }) => {
        const user = createUser(USER_PREFIXES.worker);
        await registrationSteps.registerShelterWorker(user);
        await dashboardSteps.expectGreeting(user.name);
        await dashboardSteps.expectRoleBadge(ROLE_LABELS.shelterWorker);
    });

    test('should open the shelter setup page after a shelter owner registration', async ({ registrationSteps, createUser }) => {
        const user = createUser(USER_PREFIXES.owner);
        await registrationSteps.registerShelterOwner(user);
    });
});
