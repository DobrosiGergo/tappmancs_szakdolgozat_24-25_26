import { defineConfig } from '@playwright/test';
import { configFor } from './playwright.config.base';

/**
 * Run against the deployed site: npm run test:e2e:prod
 *
 * The whole suite runs here, including the tests that create data. The
 * createUser fixture deletes the accounts it handed out after each test (see
 * src/fixtures/test.ts), and the nightly demo:reset on the server restores
 * the seeded state in full.
 *
 * One worker and two retries, because the server is a small droplet reached
 * over the network, not a local development server.
 */
export default defineConfig({
    ...configFor('production'),
    workers: 1,
    retries: 2,
});
