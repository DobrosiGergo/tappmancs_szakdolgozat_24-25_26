import path from 'node:path';
import dotenv from 'dotenv';

const LOCAL_BASE_URL = 'http://127.0.0.1:8000';
const PRODUCTION_BASE_URL = 'https://tappmancs-szakdolgozat.hu';

/**
 * The values of app-e2e/.env. They are kept in their own object instead of
 * being written into process.env, so a variable exported in the shell always
 * takes precedence over the file, in the main process and in every worker.
 */
const fileEnv: Record<string, string> =
    dotenv.config({
        path: path.resolve(__dirname, '../../.env'),
        processEnv: {},
        quiet: true,
    }).parsed ?? {};

function required(name: string): string {
    const value = process.env[name] || fileEnv[name];

    if (!value) {
        throw new Error(`Missing ${name}. Copy app-e2e/.env.example to app-e2e/.env and fill it in, or export the variable.`);
    }

    return value;
}

function testUser(prefix: string) {
    return {
        email: required(`${prefix}_EMAIL`),
        password: required(`${prefix}_PASSWORD`),
        name: required(`${prefix}_NAME`),
    };
}

/**
 * The deployment the tests run against. The Playwright config file in use
 * sets E2E_TARGET (see configFor() in playwright.config.base.ts).
 */
export function targetName(): 'production' | 'local' {
    return process.env.E2E_TARGET === 'production' ? 'production' : 'local';
}

/**
 * The address of the site under test: the default of the target, or
 * E2E_BASE_URL when it is set.
 *
 * The tests register accounts and create shelters and pets on the site they
 * run against, so an address that does not match the target is an error: the
 * local config must not write to a deployed site by accident.
 */
function resolveBaseUrl(): string {
    const target = targetName();
    const baseUrl = process.env.E2E_BASE_URL || (target === 'production' ? PRODUCTION_BASE_URL : LOCAL_BASE_URL);
    const isLocalUrl = /^https?:\/\/(localhost|127\.0\.0\.1)/.test(baseUrl);

    if (isLocalUrl !== (target === 'local')) {
        throw new Error(
            `E2E_BASE_URL (${baseUrl}) does not match the ${target} config. ` + `Use "npm run test:e2e" for a local server and "npm run test:e2e:prod" for a deployed site.`
        );
    }

    return baseUrl;
}

export const env = {
    // A getter, because E2E_TARGET is only set after this module is imported.
    get baseUrl(): string {
        return resolveBaseUrl();
    },
    newUserPassword: required('E2E_NEW_USER_PASSWORD'),
    user: testUser('E2E_USER'),
    shelterOwner: testUser('E2E_OWNER'),
    shelterWorker: testUser('E2E_WORKER'),
};
