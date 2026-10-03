import { Locator, Page } from '@playwright/test';
import { HEADINGS, LINKS } from '../../utils/labels';
import { PATHS } from '../../utils/routes';
import { MainPage } from './components/main.page';

export class DashboardPage extends MainPage {
    readonly heroTitle: Locator;

    readonly browsePetsLink: Locator;

    readonly browseSheltersLink: Locator;

    readonly userName: Locator;

    readonly roleBadge: Locator;

    readonly leaveShelterButton: Locator;

    constructor(page: Page) {
        super(page, PATHS.dashboard);
        this.heroTitle = page.getByRole('heading', {
            name: HEADINGS.dashboardHero,
        });
        this.browsePetsLink = page.getByRole('link', { name: LINKS.browsePets });
        this.browseSheltersLink = page.getByRole('link', { name: LINKS.browseShelters });
        this.userName = page.getByTestId('user-name');
        this.roleBadge = page.getByTestId('user-role-badge');
        this.leaveShelterButton = page.getByTestId('leave-shelter-button');
    }
}
