// eslint.config.cjs — ESLint 9 flat config (CJS) + TS + Playwright + Prettier
const js = require('@eslint/js');
const tseslint = require('typescript-eslint');
const prettier = require('eslint-plugin-prettier');
const { FlatCompat } = require('@eslint/eslintrc');
const globals = require('globals');

const compat = new FlatCompat({
  baseDirectory: __dirname,
});

module.exports = [
  js.configs.recommended,

  ...tseslint.configs.recommended,

  ...compat.extends('plugin:playwright/recommended'),

  {
    plugins: { prettier },
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
    rules: {
      'prettier/prettier': 'warn',
      '@typescript-eslint/no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
    },
    ignores: [
      'node_modules',
      'dist',
      'playwright-report',
      'test-results',
      '.husky',
      '.vscode',
    ],
  },

  {
    files: ['**/*.spec.ts', '**/*.test.ts'],
    rules: {
      '@typescript-eslint/no-explicit-any': 'off',
      'playwright/expect-expect': 'off',
    },
  },
];
