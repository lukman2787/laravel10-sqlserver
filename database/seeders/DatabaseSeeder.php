<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Company::factory(10)->create();

        $this->call([
            PermissionSeeder::class,
            UsersSeeder::class,
        ]);
    }
}
