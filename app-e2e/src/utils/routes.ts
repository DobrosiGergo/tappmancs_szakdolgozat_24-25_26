export const PATHS = {
    home: '/',
    about: '/about',
    login: '/login',
    register: '/register',
    registerRole: '/register/role',
    registerShelterRole: '/register/shelter/role',
    dashboard: '/dashboard',
    pets: '/pets',
    petCreate: '/pets/create',
    petsManage: '/pets/manage',
    shelters: '/shelters',
    shelterStaffing: '/shelter',
    shelterSetup: '/shelter/setup',
    settings: '/settings',
    settingsDelete: '/settings/delete',
};

export const LIVEWIRE_UPDATE_PATH = '/livewire/update';

export const ROUTES = {
    login: /\/login$/,
    dashboard: /\/dashboard$/,
    about: /\/about$/,
    pets: /\/pets/,
    petDetail: /\/pets\/[0-9a-f-]+$/,
    petsManage: /\/pets\/manage$/,
    shelters: /\/shelters/,
    shelterDetail: /\/shelters\/[0-9a-f-]+$/,
};

export function homeUrl(baseUrl: string): RegExp {
    return new RegExp(`${baseUrl}/?$`);
}
