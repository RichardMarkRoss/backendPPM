<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'create_user',
            'edit_user',
            'delete_user',
            'view_reports',
            'manage_roles',
            'approve_transactions'
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }

        // Assign Permissions to Roles
        $adminRole = Role::where('name', 'Admin')->first();
        $managementRole = Role::where('name', 'Management')->first();
        $childRole = Role::where('name', 'Child')->first();

        if ($adminRole) {
            // Admin gets ALL permissions
            $adminRole->permissions()->sync(Permission::pluck('id'));
        }

        if ($managementRole) {
            // Management gets SOME permissions
            $managementRole->permissions()->sync(
                Permission::whereIn('name', ['create_user', 'edit_user', 'view_reports'])->pluck('id')
            );
        }

        if ($childRole) {
            // Child gets FEW permissions
            $childRole->permissions()->sync(
                Permission::whereIn('name', ['view_reports'])->pluck('id')
            );
        }
    }
}
