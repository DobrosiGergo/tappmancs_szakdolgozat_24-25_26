import { env } from '../config/env';
import { E2E_ENTITY_PREFIX } from './form-texts';

export type NewUser = {
    email: string;
    name: string;
    password: string;
};

export const INVALID_PASSWORD = 'DefinitelyNotTheRightPassword123';

export function uniqueUser(prefix: string): NewUser {
    const id = Date.now();
    return {
        email: `e2e-${prefix}-${id}@example.com`,
        name: `${E2E_ENTITY_PREFIX} ${prefix} ${id}`,
        password: env.newUserPassword,
    };
}
