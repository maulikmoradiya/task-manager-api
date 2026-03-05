<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'title' => 'Create Login API',
            'description' => 'Implement Sanctum authentication',
            'status' => 'Todo',
            'assigned_to' => 3,
            'project_id' => 1,
            'due_date' => now()->addDays(5)
        ]);

        Task::create([
            'title' => 'Build Task CRUD',
            'description' => 'Create task management endpoints',
            'status' => 'InProgress',
            'assigned_to' => 3,
            'project_id' => 1,
            'due_date' => now()->addDays(7)
        ]);

        Task::create([
            'title' => 'Design Dashboard',
            'description' => 'Frontend admin dashboard',
            'status' => 'Todo',
            'assigned_to' => 4,
            'project_id' => 2,
            'due_date' => now()->addDays(10)
        ]);
    }
}
