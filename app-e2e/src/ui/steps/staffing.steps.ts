import { Page, expect } from '@playwright/test';
import { WorkerRowComponent } from '../po/components/worker-row.component';
import { StaffingPage } from '../po/staffing.page';
import { BaseSteps } from './base.steps';
import { CommonSteps } from './common.steps';

export class StaffingSteps extends BaseSteps {
    constructor(
        page: Page,
        private readonly staffingPage: StaffingPage,
        private readonly commonSteps: CommonSteps
    ) {
        super(page);
    }

    async openStaffingFromNavbar(): Promise<void> {
        const { navbar } = this.basePage;
        await this.clickInNavbarMenu(navbar.staffMenuItem, navbar.manageStaffLink);
        await expect(this.staffingPage.heading).toBeVisible();
    }

    async addWorker(email: string): Promise<void> {
        await this.staffingPage.emailInput.fill(email);
        await this.staffingPage.addButton.click();
        await expect(this.workerRow(email).locator).toBeVisible();
    }

    async ensureWorkerEmployed(email: string): Promise<void> {
        if (await this.workerRow(email).locator.isVisible()) {
            return;
        }
        await this.addWorker(email);
    }

    async removeWorker(email: string): Promise<void> {
        this.commonSteps.acceptNextDialog();
        await this.workerRow(email).removeButton.click();
        await expect(this.workerRow(email).locator).toBeHidden();
    }

    private workerRow(email: string): WorkerRowComponent {
        return new WorkerRowComponent(this.staffingPage.workerRows.filter({ hasText: email }));
    }
}
