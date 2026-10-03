import { env } from '../../../config/env';
import { test } from '../../../fixtures/test';

test.describe('Profile settings @ui @settings', () => {
    test.beforeEach(async ({ authSteps }) => {
        await authSteps.login();
    });

    test('should show every settings card', async ({ settingsSteps }) => {
        await settingsSteps.expectSettingsIndex();
    });

    test('should prefill the profile form with the user data', async ({ settingsSteps }) => {
        await settingsSteps.expectProfileForm(env.user.name, env.user.email);
    });

    test('should render every field of the password change form', async ({ settingsSteps }) => {
        await settingsSteps.expectPasswordForm();
    });

    test('should load the account delete page', async ({ settingsSteps }) => {
        await settingsSteps.expectDeletePage();
    });
});
