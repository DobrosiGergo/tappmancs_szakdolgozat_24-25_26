<?php

namespace App\Console\Commands;

use Database\Seeders\DemoSeeder;
use Illuminate\Console\Command;

class DemoSeedCommand extends Command
{
    protected $signature = 'demo:seed';

    protected $description = 'Demo adatok betöltése (owner, shelter, 3 pet, worker, user)';

    public function handle(): int
    {
        $this->call('db:seed', ['--class' => DemoSeeder::class]);

        $this->info('Demo adatok sikeresen betöltve.');
        $this->line('');
        $this->line('  Tulajdonos:  ' . DemoSeeder::OWNER_EMAIL);
        $this->line('  Munkatárs:   ' . DemoSeeder::WORKER_EMAIL);
        $this->line('  Felhasználó: ' . DemoSeeder::USER_EMAIL);
        $this->line('  Jelszó:      ' . DemoSeeder::PASSWORD);

        return Command::SUCCESS;
    }
}
