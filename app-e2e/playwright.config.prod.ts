import { defineConfig } from '@playwright/test';
import { configFor } from './playwright.config.base';

export default defineConfig({
    ...configFor('production'),
    workers: 1,
    retries: 2,
});
