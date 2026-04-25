<?php

use App\Http\Controllers\PetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShelterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/shelters', [ShelterController::class, 'index'])->name('shelters.index');

Route::get('/shelters/{shelter:uuid}', [ShelterController::class, 'show'])
    ->whereUuid('shelter')
    ->name('shelters.show');

Route::prefix('pets')->name('pets.')->group(function () {
    Route::get('/', [PetController::class, 'index'])->name('index');

    Route::get('/{pet:uuid}', [PetController::class, 'show'])
        ->whereUuid('pet')
        ->name('show');
});

Route::middleware(['auth'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/profile', 'editProfile')->name('profile');
        Route::put('/profile', 'updateProfile')->name('profile.update');
        Route::get('/password', 'editPassword')->name('password');
        Route::get('/delete', 'editDelete')->name('delete');
        Route::delete('/delete', 'deleteAccount')->name('delete.confirm');
    });

    Route::prefix('shelter')->name('shelter.')->group(function () {

        Route::get('/create', [ShelterController::class, 'create'])->name('create');
        Route::post('/', [ShelterController::class, 'store'])->name('store');
        Route::get('/setup', [ShelterController::class, 'setup'])->name('setup');

        Route::get('/{shelter:uuid}/edit', [ShelterController::class, 'edit'])
            ->whereUuid('shelter')
            ->middleware('can:update,shelter')
            ->name('edit');

        Route::put('/{shelter:uuid}', [ShelterController::class, 'update'])
            ->whereUuid('shelter')
            ->middleware('can:update,shelter')
            ->name('update');
    });

    Route::prefix('pets')->name('pets.')->middleware('role:Shelterowner,Shelterworker')->group(function () {

        Route::get('/create', [PetController::class, 'create'])->name('create');
        Route::post('/', [PetController::class, 'store'])->name('store');

        Route::get('/manage', [PetController::class, 'updateIndex'])->name('update.index');

        Route::get('/{pet:uuid}/edit', [PetController::class, 'edit'])
            ->whereUuid('pet')
            ->name('edit');

        Route::put('/{pet:uuid}', [PetController::class, 'update'])
            ->whereUuid('pet')
            ->name('update');

        Route::delete('/{pet:uuid}', [PetController::class, 'destroy'])
            ->whereUuid('pet')
            ->name('destroy');
    });
});

Route::get('/shelters/{id}', function ($id) {

    if (! ctype_digit((string) $id)) {
        abort(404);
    }

    $shelter = \App\Models\Shelter::findOrFail((int) $id);

    return redirect()->route('shelters.show', $shelter, 301);

})->whereNumber('id');

require __DIR__ . '/auth.php';