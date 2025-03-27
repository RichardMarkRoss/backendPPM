<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeding roles and permissions in proper order
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);
    }
}
