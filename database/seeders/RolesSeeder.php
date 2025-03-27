<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        try {
            $roles = [
                ['name' => 'Admin', 'parent_role_id' => null], // Top-level role
                ['name' => 'Management', 'parent_role_id' => 1], // Admin is parent
                ['name' => 'Child', 'parent_role_id' => 2], // Management is parent
            ];
    
            foreach ($roles as $role) {
                Role::firstOrCreate($role);
            }

            DB::commit();

            $this->command->info('✅ Roles seeded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Failed to seed roles: ' . $e->getMessage());
        }
    }
}
