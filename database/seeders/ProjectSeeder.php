<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'name' => 'Task Management API',
            'description' => 'Backend system for managing tasks',
            'team_id' => 1,
            'created_by' => 1
        ]);

        Project::create([
            'name' => 'Frontend Dashboard',
            'description' => 'Admin dashboard project',
            'team_id' => 2,
            'created_by' => 1
        ]);
    }
}
