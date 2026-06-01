<?php

namespace App\Console\Commands;

use App\Models\Form;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Console\Command;

class DemoResetCommand extends Command
{
    protected $signature = 'demo:reset';

    protected $description = 'Demo adatok törlése és újratöltése';

    public function handle(): int
    {
        $emails = [
            DemoSeeder::OWNER_EMAIL,
            DemoSeeder::WORKER_EMAIL,
            DemoSeeder::USER_EMAIL,
        ];

        $owner = User::where('email', DemoSeeder::OWNER_EMAIL)->first();

        if ($owner) {
            $shelter = Shelter::where('owner_id', $owner->id)->first();

            if ($shelter) {
                Form::where('shelter_id', $shelter->id)->delete();
                Pet::where('shelter_id', $shelter->id)->delete();
                $shelter->delete();
            }
        }

        User::whereIn('email', $emails)->delete();

        $this->info('Demo adatok törölve.');

        $this->call('db:seed', ['--class' => DemoSeeder::class]);

        $this->info('Demo adatok sikeresen újratöltve.');
        $this->line('');
        $this->line('  Tulajdonos:  ' . DemoSeeder::OWNER_EMAIL);
        $this->line('  Munkatárs:   ' . DemoSeeder::WORKER_EMAIL);
        $this->line('  Felhasználó: ' . DemoSeeder::USER_EMAIL);
        $this->line('  Jelszó:      ' . DemoSeeder::PASSWORD);

        return Command::SUCCESS;
    }
}
