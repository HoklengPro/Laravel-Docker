<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::whereHas('roles', function($q) {
            $q->where('name', 'staff');
        })->get();

        if ($staff->count() === 0) {
            return;
        }

        $projects = Project::all();

        if ($projects->count() === 0) {
            return;
        }

        // Tasks for first project
        $project1 = $projects->first();
        
        Task::create([
            'title' => 'Setup Laravel project structure',
            'description' => 'Initialize Laravel project with required dependencies',
            'project_id' => $project1->id,
            'assigned_to' => $staff[0]->id,
            'status' => 'completed',
        ]);

        Task::create([
            'title' => 'Design database schema',
            'description' => 'Create ERD and database migration files',
            'project_id' => $project1->id,
            'assigned_to' => $staff[0]->id,
            'status' => 'in_progress',
        ]);

        Task::create([
            'title' => 'Implement authentication',
            'description' => 'Setup user authentication with Laravel Breeze',
            'project_id' => $project1->id,
            'assigned_to' => $staff->count() > 1 ? $staff[1]->id : $staff[0]->id,
            'status' => 'pending',
        ]);

        // Tasks for second project (if exists)
        if ($projects->count() > 1) {
            $project2 = $projects->skip(1)->first();
            
            Task::create([
                'title' => 'API endpoint design',
                'description' => 'Define all RESTful API endpoints',
                'project_id' => $project2->id,
                'assigned_to' => $staff[0]->id,
                'status' => 'in_progress',
            ]);

            Task::create([
                'title' => 'Implement CRUD operations',
                'description' => 'Create controllers for all resources',
                'project_id' => $project2->id,
                'assigned_to' => $staff->count() > 1 ? $staff[1]->id : $staff[0]->id,
                'status' => 'pending',
            ]);
        }

        // Tasks for third project (if exists)
        if ($projects->count() > 2) {
            $project3 = $projects->skip(2)->first();
            
            Task::create([
                'title' => 'UI/UX mockup review',
                'description' => 'Review and approve new dashboard designs',
                'project_id' => $project3->id,
                'assigned_to' => $staff[0]->id,
                'status' => 'pending',
            ]);
        }
    }
}
