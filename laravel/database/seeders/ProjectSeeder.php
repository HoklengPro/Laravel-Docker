<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::whereHas('roles', function($q) {
            $q->where('name', 'manager');
        })->first();

        if ($manager) {
            Project::create([
                'name' => 'E-commerce Platform',
                'description' => 'Build a modern e-commerce platform with Laravel and Vue.js',
                'created_by' => $manager->id,
                'status' => 'active',
            ]);

            Project::create([
                'name' => 'Mobile App Backend',
                'description' => 'RESTful API development for mobile application',
                'created_by' => $manager->id,
                'status' => 'active',
            ]);

            Project::create([
                'name' => 'Dashboard Redesign',
                'description' => 'Modernize admin dashboard with better UX',
                'created_by' => $manager->id,
                'status' => 'on_hold',
            ]);
        }
    }
}
