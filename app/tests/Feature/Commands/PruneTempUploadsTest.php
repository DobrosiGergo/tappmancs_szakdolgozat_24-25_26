<?php

use Illuminate\Support\Facades\Storage;

it('prune-temp-uploads command exits with success code', function () {
    Storage::fake('public');

    $this->artisan('uploads:prune', ['--hours' => 24])->assertExitCode(0);
});

it('prune-temp-uploads does not delete recently uploaded files', function () {
    Storage::fake('public');
    Storage::disk('public')->put('temp/recent.jpg', 'content');

    $this->artisan('uploads:prune', ['--hours' => 24])->assertExitCode(0);

    Storage::disk('public')->assertExists('temp/recent.jpg');
});

it('prune-temp-uploads deletes files older than the specified hours', function () {
    Storage::fake('public');
    Storage::disk('public')->put('temp/old.jpg', 'content');

    $oldPath = Storage::disk('public')->path('temp/old.jpg');
    touch($oldPath, now()->subHours(25)->timestamp);

    $this->artisan('uploads:prune', ['--hours' => 24])->assertExitCode(0);

    Storage::disk('public')->assertMissing('temp/old.jpg');
});
