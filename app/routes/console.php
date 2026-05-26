<?php

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Ideiglenes feltöltések törlése (temp storage tisztítás)
|--------------------------------------------------------------------------
*/

Schedule::command('uploads:prune')->daily();
