<?php

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Ideiglenes feltöltések törlése (temp storage tisztítás)
|--------------------------------------------------------------------------
*/

Schedule::command('uploads:prune')->daily();

/*
|--------------------------------------------------------------------------
| Demo adatok napi visszaállítása
|--------------------------------------------------------------------------
|
| A bemutatóhoz használt fiókok és tartalom minden hajnalban visszaáll, így a
| próbálgatás nem hagy maga után rendetlenséget.
|
*/

Schedule::command('demo:reset')->dailyAt('04:00');
