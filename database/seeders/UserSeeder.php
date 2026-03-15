<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Developer
        User::create([
            'name'              => 'Developer User',
            'email'             => 'developer@exapmle.com',
            'password'          => Hash::make('DeveloperPassword123'),
            'phoneNumber'       => '111111111',
            'type'              => 'Developer',
            'email_verified_at' => now(),
        ]);

        // Shelterowner
        User::create([
            'name'              => 'Shelter Owner',
            'email'             => 'shelterowner@example.com',
            'password'          => Hash::make('ShelterOwnerPassword123'),
            'phoneNumber'       => '222222222',
            'type'              => 'Shelterowner',
            'email_verified_at' => now(),
        ]);

        // Shelterworker
        User::create([
            'name'              => 'Shelter Worker',
            'email'             => 'shelterworker@example.com',
            'password'          => Hash::make('ShelterWorkerPassword123'),
            'phoneNumber'       => '333333333',
            'type'              => 'Shelterworker',
            'email_verified_at' => now(),
        ]);

        // Regular User
        User::create([
            'name'              => 'Regular User',
            'email'             => 'user@example.com',
            'password'          => Hash::make('RegularUserPassword123'),
            'phoneNumber'       => '444444444',
            'type'              => 'User',
            'email_verified_at' => now(),
        ]);

        // 10 random User
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name'              => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'password'          => Hash::make('RandomUserPassword123'), // ugyanaz a jelszó, hogy ha kell belépni tudd
                'phoneNumber'       => $faker->phoneNumber,
                'type'              => 'User',
                'email_verified_at' => now(),
            ]);
        }

        // 3 random Shelterworker
        for ($i = 0; $i < 3; $i++) {
            User::create([
                'name'              => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'password'          => Hash::make('RandomShelterWorkerPassword123'),
                'phoneNumber'       => $faker->phoneNumber,
                'type'              => 'Shelterworker',
                'email_verified_at' => now(),
            ]);
        }

        // 2 random Shelterowner (extra)
        for ($i = 0; $i < 2; $i++) {
            User::create([
                'name'              => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'password'          => Hash::make('RandomShelterOwnerPassword123'),
                'phoneNumber'       => $faker->phoneNumber,
                'type'              => 'Shelterowner',
                'email_verified_at' => now(),
            ]);
        }
    }
}
