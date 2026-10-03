import { env } from '../../../config/env';
import { test } from '../../../fixtures/test';
import { INVALID_PASSWORD } from '../../../utils/users';

test.describe('Login @ui @smoke @auth', () => {
    test('should land on the dashboard after a successful login', async ({ authSteps, dashboardSteps }) => {
        await authSteps.login();
        await dashboardSteps.expectGreeting();
    });

    test('should show an error message for an invalid password', async ({ authSteps }) => {
        await authSteps.loginExpectingError(env.user.email, INVALID_PASSWORD);
    });

    test('should return to the home page after logout', async ({ authSteps, homeSteps }) => {
        await authSteps.login();
        await authSteps.logout();
        await homeSteps.expectBackOnHomePage();
    });
});
