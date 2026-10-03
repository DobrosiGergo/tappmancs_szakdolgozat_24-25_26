export const APP_NAME = 'Tappmancs';

export type PetStatus = 'free' | 'reserved' | 'adopted';

export const PET_STATUSES: Record<PetStatus, PetStatus> = {
    free: 'free',
    reserved: 'reserved',
    adopted: 'adopted',
};

export const ROLE_LABELS = {
    user: 'Felhasználó',
    shelterWorker: 'Munkatárs',
    shelterOwner: 'Menhely tulajdonos',
};

export const HEADINGS = {
    login: 'Bejelentkezés',
    register: 'Hozz létre saját fiókot',
    shelterRoleInfo: 'Szerepkörök információ',
    dashboardHero: 'Kezdjük el a jót: segítsünk új otthont találni',
    settings: 'Beállítások',
    profileEdit: 'Profiladatok módosítása',
    passwordEdit: 'Jelszó módosítása',
    accountDelete: 'Fiók törlése',
    petsList: 'Elérhető kisállatok',
    petsManage: 'Saját feltöltött kisállatok',
    petCreate: 'Új kisállat felvétele',
    petEdit: 'Kisállat módosítása',
    petBasicData: 'Alapadatok',
    petAdoptionInterest: 'Érdekel az örökbefogadás?',
    petStatus: 'Státusz',
    contact: 'Kapcsolatfelvétel',
    sheltersList: 'Elérhető menhelyek',
    shelterAbout: 'A menhelyről',
    shelterCreate: 'Hozza létre menhelyét',
    shelterEdit: 'Menhely szerkesztése',
    staffing: 'Munkatársak kezelése',
};

export const LINKS = {
    browsePets: 'Kisállatok megtekintése',
    browseShelters: 'Menhelyek böngészése',
    viewShelter: 'Menhely megtekintése',
};
