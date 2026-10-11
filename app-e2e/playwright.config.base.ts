import { devices, type PlaywrightTestConfig } from '@playwright/test';
import { env } from './src/config/env';

/**
 * The settings shared by the local and the production config.
 */
export function configFor(target: 'local' | 'production'): PlaywrightTestConfig {
    // The worker processes inherit the environment, and src/config/env.ts
    // reads the target from it to resolve the base URL in each of them.
    process.env.E2E_TARGET = target;

    return {
        testDir: './src',
        globalSetup: './src/config/global-setup.ts',
        fullyParallel: true,
        forbidOnly: !!process.env.CI,
        retries: process.env.CI ? 2 : 0,
        workers: process.env.CI ? 1 : 4,
        reporter: 'html',
        use: {
            baseURL: env.baseUrl,
            testIdAttribute: 'data-test-id',
            trace: 'on-first-retry',
        },

        projects: [
            {
                name: 'chromium',
                use: { ...devices['Desktop Chrome'] },
            },
            {
                name: 'firefox',
                use: { ...devices['Desktop Firefox'] },
            },
        ],
    };
}
