<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Structure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesStructuresPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $nationalRole = Role::create(['name' => 'National']);
        $cityRole = Role::create(['name' => 'City', 'parent_role_id' => $nationalRole->id]);
        $suburbRole = Role::create(['name' => 'Suburb', 'parent_role_id' => $cityRole->id]);
    
        $nationalStructure = Structure::create(['name' => 'National']);
        $cityStructure = Structure::create(['name' => 'Cape Town', 'parent_structure_id' => $nationalStructure->id]);
        $suburbStructure = Structure::create(['name' => 'Waterfront', 'parent_structure_id' => $cityStructure->id]);
    
        $viewUsersPermission = Permission::create(['name' => 'view_users']);
        $nationalRole->permissions()->attach($viewUsersPermission);
        $cityRole->permissions()->attach($viewUsersPermission);
        $suburbRole->permissions()->attach($viewUsersPermission);
    }
}
