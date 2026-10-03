export const E2E_ENTITY_PREFIX = 'E2E';

export const SHELTER_TEXTS = {
    location: 'Budapest',
    updatedLocation: 'Debrecen',
    description: 'Automatizált e2e teszt menhely leírása, amely elég hosszú a validációhoz.',
    updatedDescription: 'Automatizált e2e teszt menhely frissített leírása, továbbra is elég hosszú.',
};

export const PET_TEXTS = {
    description: 'Automatizált e2e teszt kisállat leírása, amely elég hosszú a validációhoz.',
};

export const CONTACT_MESSAGE = 'Szia! Nagyon megtetszett ez a kisállat, szívesen örökbe fogadnám. (Automatizált e2e teszt üzenet.)';

export function uniqueName(label: string): string {
    return `${E2E_ENTITY_PREFIX} ${label} ${Date.now()}`;
}
