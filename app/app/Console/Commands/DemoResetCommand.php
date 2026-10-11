<?php

namespace App\Console\Commands;

use App\Support\UploadDisk;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Returns the site to the exact state the seeders describe.
 *
 * Every data table is emptied, not just the demo accounts, so the deployed
 * site is in the same known state every morning, whatever visitors or the
 * e2e tests left behind the day before.
 */
class DemoResetCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'demo:reset {--force : Skip the production confirmation, as the scheduler does}';

    protected $description = 'Minden adat törlése és a demo állapot visszaállítása';

    /**
     * Every table that holds site data. The framework's own tables (cache,
     * cache_locks, jobs, job_batches, failed_jobs) are not listed; the cache
     * is cleared separately with cache:clear.
     */
    private const TABLES = [
        'likes',
        'comments',
        'adoptions',
        'form_messages',
        'pets',
        'shelter',
        'breeds',
        'species',
        'notifications',
        'password_reset_tokens',
        'sessions',
        'users',
    ];

    /**
     * The directories uploaded images are stored in: 'temp' while an upload
     * is in progress, then 'pets' and 'shelters' once the form is saved.
     *
     * The seeders create no images, so every file in these directories
     * belongs to data that is being removed. Livewire's own 'livewire-tmp'
     * directory is not touched.
     */
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

        // db:seed asks for confirmation in production, and the scheduler
        // cannot answer it. Without --force the tables would stay empty.
        $this->call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
        $this->call('db:seed', ['--class' => DemoSeeder::class, '--force' => true]);

        $this->info('A demo állapot visszaállt.');
        $this->line('');
        $this->line('  Tulajdonos:  ' . DemoSeeder::OWNER_EMAIL);
        $this->line('  Munkatárs:   ' . DemoSeeder::WORKER_EMAIL);
        $this->line('  Felhasználó: ' . DemoSeeder::USER_EMAIL);
        $this->line('  Jelszó:      ' . DemoSeeder::PASSWORD);

        return Command::SUCCESS;
    }

    /** @return int the number of rows removed */
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

    /** @return int the number of files removed */
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
