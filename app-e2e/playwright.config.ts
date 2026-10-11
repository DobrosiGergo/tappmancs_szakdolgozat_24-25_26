import { defineConfig } from '@playwright/test';
import { configFor } from './playwright.config.base';

/**
 * Run against the local development server: npm run test:e2e
 *
 * This file has Playwright's default config name, so `npx playwright test`
 * and the editor extensions use it without a --config option.
 */
export default defineConfig(configFor('local'));
