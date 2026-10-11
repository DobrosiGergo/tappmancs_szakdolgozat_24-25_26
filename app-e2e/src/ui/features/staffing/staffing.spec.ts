import { env } from '../../../config/env';
import { test } from '../../../fixtures/test';
import { USER_PREFIXES } from '../../../utils/users';

test.describe('Staffing management @ui @staffing', () => {
    test('should let an owner add a worker and then remove them', async ({ registrationSteps, authSteps, staffingSteps, createUser }) => {
        const newWorker = createUser(USER_PREFIXES.staff);

        await registrationSteps.registerAdopter(newWorker);
        await authSteps.logout();
        await authSteps.login(env.shelterOwner.email, env.shelterOwner.password);
        await staffingSteps.openStaffingFromNavbar();
        await staffingSteps.addWorker(newWorker.email);
        await staffingSteps.removeWorker(newWorker.email);
    });
});
