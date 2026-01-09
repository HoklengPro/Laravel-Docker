<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator - manage users + everything']
        );

        Role::firstOrCreate(
            ['name' => 'manager'],
            ['description' => 'Manager - create/update projects + assign tasks']
        );

        Role::firstOrCreate(
            ['name' => 'staff'],
            ['description' => 'Staff - view assigned tasks + update task status']
        );
    }
}
