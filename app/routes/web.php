<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\StaffingController;
use App\Http\Controllers\WelcomeController;
use App\Models\Shelter;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publikus route-ok (auth nélkül is elérhetők)
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    $resultsPath = resource_path('data/e2e-results.json');

    return view('about', [
        'e2e' => file_exists($resultsPath) ? json_decode(file_get_contents($resultsPath), true) : null,
    ]);
})->name('about');

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

    /*
    | Fiókbeállítások: minden bejelentkezett felhasználónak elérhető.
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
    | Menhely-kezelés. A setup a tulajdonosi regisztráció utolsó lépése,
    | a szerkesztést és a munkatársak kezelését a ShelterPolicy a menhely
    | tulajdonosára korlátozza.
    */
    Route::prefix('shelter')->name('shelter.')->group(function () {

        Route::get('/setup', [ShelterController::class, 'setup'])->name('setup');

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
    | Kisállat-kezelés: csak menhely-tulajdonos vagy munkatárs.
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
    | A munkatárs saját maga is kiléphet a menhelyből.
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
| A régi, numerikus azonosítós menhely-URL-ek átirányítása az UUID-s címre
|--------------------------------------------------------------------------
*/

Route::get('/shelters/{id}', function ($id) {
    $shelter = Shelter::findOrFail((int) $id);

    return redirect()->route('shelters.show', $shelter, 301);
})->whereNumber('id');

require __DIR__ . '/auth.php';
