import { env, targetName } from './env';

const PROBE_TIMEOUT_MS = 10_000;

/**
 * Checks that the site under test answers before any test runs, and prints
 * which site that is. Without this a server that is not running would show up
 * as every test failing on its first navigation.
 *
 * The message goes to stderr, because `npm run test:e2e:export` redirects
 * stdout (the JSON report) into a file.
 */
export default async function globalSetup(): Promise<void> {
    const target = targetName();
    const hint = target === 'local' ? 'Start the development server first: cd app && php artisan serve.' : 'Check the droplet and the nginx service before rerunning.';

    let status: number;

    try {
        const response = await fetch(env.baseUrl, {
            signal: AbortSignal.timeout(PROBE_TIMEOUT_MS),
            redirect: 'follow',
        });
        status = response.status;
    } catch (error) {
        const reason = error instanceof Error ? error.message : String(error);
        throw new Error(`${env.baseUrl} is not answering (${reason}). ${hint}`);
    }

    if (status >= 400) {
        throw new Error(`${env.baseUrl} answered HTTP ${status}. ${hint}`);
    }

    console.error(`\n${target}: ${env.baseUrl} (HTTP ${status})\n`);
}
