<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1 admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Create 1 manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $manager->roles()->attach(Role::where('name', 'manager')->first());

        // Create 2 staff users
        $staff1 = User::create([
            'name' => 'Staff User 1',
            'email' => 'staff1@example.com',
            'password' => Hash::make('password'),
        ]);
        $staff1->roles()->attach(Role::where('name', 'staff')->first());

        $staff2 = User::create([
            'name' => 'Staff User 2',
            'email' => 'staff2@example.com',
            'password' => Hash::make('password'),
        ]);
        $staff2->roles()->attach(Role::where('name', 'staff')->first());
    }
}
