<?php

namespace App\Console\Commands;

use App\Support\UploadDisk;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoResetCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'demo:reset {--force : Skip the production confirmation, as the scheduler does}';

    protected $description = 'Minden adat törlése és a demo állapot visszaállítása';

    private const TABLES = [
        'likes',
        'comments',
        'adoptions',
        'form_messages',
        'pets',
        'shelter',
        'breeds',
        'species',
        'password_reset_tokens',
        'sessions',
        'users',
    ];

    private const UPLOAD_DIRECTORIES = ['pets', 'shelters', 'temp'];

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return Command::FAILURE;
        }

        $rows  = $this->truncateTables();
        $files = $this->deleteUploads();

        $this->call('cache:clear');

        $this->info("Törölve: {$rows} sor, {$files} feltöltött fájl.");

        $this->call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
        $this->call('db:seed', ['--class' => DemoSeeder::class, '--force' => true]);

        $this->info('A demo állapot visszaállt.');
        $this->line('');
        $this->line('  Tulajdonos:   ' . DemoSeeder::OWNER_EMAIL);
        $this->line('  Munkatárs:    ' . DemoSeeder::WORKER_EMAIL);
        $this->line('  Örökbefogadó: ' . DemoSeeder::USER_EMAIL);
        $this->line('  Jelszó:       ' . DemoSeeder::PASSWORD);

        return Command::SUCCESS;
    }

    private function truncateTables(): int
    {
        $rows = 0;

        Schema::disableForeignKeyConstraints();

        try {
            foreach (self::TABLES as $table) {
                if (! Schema::hasTable($table)) {
                    continue;
                }

                $rows += DB::table($table)->count();
                DB::table($table)->truncate();
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        return $rows;
    }

    private function deleteUploads(): int
    {
        $disk  = UploadDisk::get();
        $files = 0;

        foreach (self::UPLOAD_DIRECTORIES as $directory) {
            if (! $disk->exists($directory)) {
                continue;
            }

            $files += count($disk->allFiles($directory));
            $disk->deleteDirectory($directory);
        }

        return $files;
    }
}
