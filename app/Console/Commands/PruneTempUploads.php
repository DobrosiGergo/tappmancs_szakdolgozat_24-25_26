<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneTempUploads extends Command
{
    protected $signature = 'uploads:prune {--hours=24 : Delete files older than this many hours}';

    protected $description = 'Delete abandoned temp upload files';

    public function handle(): int
    {
        $hours  = (int) $this->option('hours');
        $cutoff = now()->subHours($hours)->timestamp;
        $disk   = Storage::disk('public');
        $count  = 0;

        foreach ($disk->allFiles('temp') as $file) {
            if ($disk->lastModified($file) < $cutoff) {
                $disk->delete($file);
                $count++;
            }
        }

        $this->info("Deleted {$count} abandoned temp file(s).");

        return Command::SUCCESS;
    }
}