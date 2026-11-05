import { defineConfig } from '@playwright/test';
import { configFor } from './playwright.config.base';

export default defineConfig(configFor('local'));
