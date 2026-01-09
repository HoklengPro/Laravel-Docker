<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->permissions()->syncWithoutDetaching(Permission::pluck('id')->toArray());
        }

        $manager = Role::where('name', 'manager')->first();
        if ($manager) {
            $manager->permissions()->syncWithoutDetaching(
                Permission::whereIn('name', ['projects.create','projects.update','categories.create','categories.update'])->pluck('id')->toArray()
            );
        }

        // attach specific permissions to staff if needed
    }
}
