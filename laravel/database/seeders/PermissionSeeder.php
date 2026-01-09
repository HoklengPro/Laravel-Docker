<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.manage',
            'products.create', 'products.update', 'products.delete', 'products.view',
            'categories.create', 'categories.update', 'categories.delete', 'categories.view',
            'tasks.create', 'tasks.update', 'tasks.delete', 'tasks.view', 'tasks.updateStatus',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
