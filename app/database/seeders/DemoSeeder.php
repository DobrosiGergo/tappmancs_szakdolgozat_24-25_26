<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\Specie;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public const OWNER_EMAIL  = 'demo.owner@example.com';
    public const WORKER_EMAIL = 'demo.worker@example.com';
    public const USER_EMAIL   = 'demo.user@example.com';
    public const PASSWORD     = 'DemoPassword123';

    public function run(): void
    {
        $owner = User::create([
            'name'              => 'Demo Menhely Tulajdonos',
            'email'             => self::OWNER_EMAIL,
            'password'          => Hash::make(self::PASSWORD),
            'type'              => 'Shelterowner',
            'email_verified_at' => now(),
        ]);

        $shelter = Shelter::create([
            'name'        => 'Tappmancs Demo Menhely',
            'owner_id'    => $owner->id,
            'description' => 'Ez egy demo menhely, amely a szakdolgozat bemutatásához lett létrehozva. Három kisállatunkat szerető gazdiknak adjuk örökbe.',
            'location'    => 'Budapest',
            'images'      => [],
        ]);

        $dog    = Specie::where('name', 'Kutya')->first();
        $cat    = Specie::where('name', 'Macska')->first();
        $mixed  = Breed::where('name', 'Keverék')->first();

        $pets = [
            [
                'name'        => 'Bodri',
                'species_id'  => $dog?->id ?? 1,
                'breed_id'    => $mixed?->id ?? 1,
                'gender'      => 'male',
                'birth_date'  => now()->subYears(2)->subMonths(3),
                'arrival_date'=> now()->subMonths(6),
                'status'      => 'free',
                'description' => 'Bodri egy barátságos, energikus keverék kutya, aki imád játszani és futni. Gyerekekkel is jól kijön, és más állatokhoz is szokott.',
            ],
            [
                'name'        => 'Cirmi',
                'species_id'  => $cat?->id ?? 2,
                'breed_id'    => $mixed?->id ?? 1,
                'gender'      => 'female',
                'birth_date'  => now()->subYears(1)->subMonths(5),
                'arrival_date'=> now()->subMonths(4),
                'status'      => 'free',
                'description' => 'Cirmi egy szelíd, önálló macska, aki szereti az ölelést, de csak a saját feltételei szerint. Csendes otthont keres.',
            ],
            [
                'name'        => 'Morzsa',
                'species_id'  => $dog?->id ?? 1,
                'breed_id'    => $mixed?->id ?? 1,
                'gender'      => 'male',
                'birth_date'  => now()->subMonths(8),
                'arrival_date'=> now()->subMonths(2),
                'status'      => 'free',
                'description' => 'Morzsa egy kölyökkutya, aki még tanul mindent. Rengeteg energiával és szeretettel van tele – tökéletes aktív gazdiknak.',
            ],
        ];

        foreach ($pets as $petData) {
            Pet::create(array_merge($petData, [
                'shelter_id'  => $shelter->id,
                'employee_id' => $owner->id,
            ]));
        }

        User::create([
            'name'              => 'Demo Munkatárs',
            'email'             => self::WORKER_EMAIL,
            'password'          => Hash::make(self::PASSWORD),
            'type'              => 'Shelterworker',
            'shelter_id'        => null,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Demo Felhasználó',
            'email'             => self::USER_EMAIL,
            'password'          => Hash::make(self::PASSWORD),
            'type'              => 'User',
            'email_verified_at' => now(),
        ]);
    }
}
