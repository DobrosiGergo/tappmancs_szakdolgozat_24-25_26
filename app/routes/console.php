<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Félbehagyott képfeltöltések ideiglenes fájljainak törlése
|--------------------------------------------------------------------------
*/

Schedule::command('uploads:prune')->daily();

/*
|--------------------------------------------------------------------------
| Demo adatok napi visszaállítása
|--------------------------------------------------------------------------
|
| Minden hajnalban a teljes adatállomány (nem csak a demo fiókok) visszaáll
| arra, amit a seederek leírnak. Így a látogatók próbálgatása és az e2e
| tesztek adatai sem gyűlnek fel, az oldal pedig minden reggel bemutatható.
|
| A --force azért kell, mert a demo:reset production alatt megerősítést kér,
| az ütemezőnek pedig nincs hol válaszolnia.
|
*/

Schedule::command('demo:reset --force')->dailyAt('04:00');
