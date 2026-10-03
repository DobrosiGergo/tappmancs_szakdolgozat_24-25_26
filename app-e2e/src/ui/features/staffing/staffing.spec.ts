import { env } from '../../../config/env';
import { test } from '../../../fixtures/test';
import { uniqueUser } from '../../../utils/users';

test.describe('Staffing management @ui @staffing @mutating', () => {
    test('should let an owner add a worker and then remove them', async ({ registrationSteps, authSteps, staffingSteps, settingsSteps }) => {
        const newWorker = uniqueUser('staff');

        await registrationSteps.registerAdopter(newWorker);
        await authSteps.logout();
        await authSteps.login(env.shelterOwner.email, env.shelterOwner.password);
        await staffingSteps.openStaffingFromNavbar();
        await staffingSteps.addWorker(newWorker.email);
        await staffingSteps.removeWorker(newWorker.email);
        await authSteps.logout();
        await authSteps.login(newWorker.email, newWorker.password);
        await settingsSteps.deleteAccount(newWorker.password);
    });
});
