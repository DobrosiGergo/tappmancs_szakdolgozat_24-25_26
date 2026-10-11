import { env } from '../config/env';
import { E2E_ENTITY_PREFIX } from './form-texts';

export type NewUser = {
    email: string;
    name: string;
    password: string;
};

export const INVALID_PASSWORD = 'DefinitelyNotTheRightPassword123';

export const USER_PREFIXES = {
    adopter: 'adopter',
    worker: 'worker',
    owner: 'owner',
    shelterOwner: 'shelter-owner',
    staff: 'staff',
    sender: 'sender',
} as const;

export type UserPrefix = (typeof USER_PREFIXES)[keyof typeof USER_PREFIXES];

export function uniqueUser(prefix: UserPrefix): NewUser {
    const id = Date.now();
    return {
        email: `e2e-${prefix}-${id}@example.com`,
        name: `${E2E_ENTITY_PREFIX} ${prefix} ${id}`,
        password: env.newUserPassword,
    };
}
