import path from 'node:path';
import dotenv from 'dotenv';

dotenv.config({ path: path.resolve(__dirname, '../../.env') });

const isProduction = process.env.E2E_TARGET === 'production';

function required(name: string): string {
    const value = process.env[name];
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

function resolveBaseUrl(): string {
    const baseUrl = required('E2E_BASE_URL');
    if (isProduction && /^https?:\/\/(localhost|127\.0\.0\.1)/.test(baseUrl)) {
        throw new Error(`E2E_TARGET=production but E2E_BASE_URL points at ${baseUrl}. Set it to the deployed site.`);
    }
    return baseUrl;
}

export const env = {
    baseUrl: resolveBaseUrl(),
    newUserPassword: required('E2E_NEW_USER_PASSWORD'),
    user: testUser('E2E_USER'),
    shelterOwner: testUser('E2E_OWNER'),
    shelterWorker: testUser('E2E_WORKER'),
};
