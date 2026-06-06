<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\StaffingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publikus route-ok (auth nélkül is elérhetők)
|--------------------------------------------------------------------------
*/

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('home');

Route::view('/about', 'about')->name('about');

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

/*
|--------------------------------------------------------------------------
| Hitelesített route-ok
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/notifications', [NotificationsController::class, 'index'])
        ->name('notifications.index');

    /*
    | Fiókbeállítások – verified nélkül is elérhető,
    | hogy a felhasználó el tudja küldeni az email-megerősítést.
    */
    Route::prefix('settings')->name('settings.')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/profile', 'editProfile')->name('profile');
        Route::put('/profile', 'updateProfile')->name('profile.update');
        Route::get('/password', 'editPassword')->name('password');
        Route::get('/delete', 'editDelete')->name('delete');
        Route::delete('/delete', 'deleteAccount')->name('delete.confirm');
    });

    /*
    | Menhely-kezelés – email-megerősítés kötelező.
    | A shelter.setup a regisztrációs folyamathoz szükséges,
    | de a tényleges létrehozás/szerkesztés verified-et igényel.
    */
    Route::prefix('shelter')->name('shelter.')->group(function () {

        Route::get('/setup', [ShelterController::class, 'setup'])->name('setup');

        Route::get('/create', [ShelterController::class, 'create'])->name('create');
        Route::post('/', [ShelterController::class, 'store'])->name('store');

        Route::get('/{shelter:uuid}/edit', [ShelterController::class, 'edit'])
            ->whereUuid('shelter')
            ->middleware('can:update,shelter')
            ->name('edit');

        Route::put('/{shelter:uuid}', [ShelterController::class, 'update'])
            ->whereUuid('shelter')
            ->middleware('can:update,shelter')
            ->name('update');

        Route::get('/{shelter:uuid}/staffing', [StaffingController::class, 'index'])
            ->whereUuid('shelter')
            ->middleware('can:manageStaff,shelter')
            ->name('staffing.index');

        Route::post('/{shelter:uuid}/staffing', [StaffingController::class, 'store'])
            ->whereUuid('shelter')
            ->middleware('can:manageStaff,shelter')
            ->name('staffing.store');

        Route::delete('/{shelter:uuid}/staffing/{worker:uuid}', [StaffingController::class, 'destroy'])
            ->whereUuid('shelter')
            ->whereUuid('worker')
            ->middleware('can:manageStaff,shelter')
            ->withoutScopedBindings()
            ->name('staffing.destroy');
    });

    /*
    | Kisállat-kezelés – csak menhely-tulajdonos vagy munkatárs,
    | email-megerősítés kötelező.
    */
    Route::prefix('pets')->name('pets.')
        ->middleware(['role:Shelterowner,Shelterworker'])
        ->group(function () {

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

            Route::patch('/{pet:uuid}/status', [PetController::class, 'updateStatus'])
                ->whereUuid('pet')
                ->name('update.status');
        });

    /*
    | Munkatárs önkéntes kilépés a menhelyből.
    */
    Route::delete('/staffing/leave', [StaffingController::class, 'leave'])
        ->middleware('role:Shelterworker')
        ->name('staffing.leave');

    Route::post('/messages', [FormController::class, 'store'])
        ->middleware('role:User')
        ->name('messages.store');

    Route::prefix('messages')->name('messages.')->middleware('role:Shelterowner')->group(function () {
        Route::get('/', [FormController::class, 'index'])->name('index');
        Route::get('/{form:uuid}', [FormController::class, 'show'])->whereUuid('form')->name('show');
        Route::delete('/{form:uuid}', [FormController::class, 'destroy'])->whereUuid('form')->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Legacy ID-alapú redirect (régi numerikus URL-ek visszafelé-kompatibilitás)
|--------------------------------------------------------------------------
*/

Route::get('/shelters/{id}', function ($id) {
    $shelter = \App\Models\Shelter::findOrFail((int) $id);

    return redirect()->route('shelters.show', $shelter, 301);
})->whereNumber('id');

require __DIR__ . '/auth.php';
