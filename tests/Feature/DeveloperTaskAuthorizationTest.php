<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Task;
use App\Models\Project;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeveloperTaskAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_cannot_update_other_developer_task()
    {
        $team = Team::factory()->create();

        $dev1 = User::factory()->create([
            'role' => 'Developer',
            'team_id' => $team->id
        ]);

        $dev2 = User::factory()->create([
            'role' => 'Developer',
            'team_id' => $team->id
        ]);

        $project = Project::factory()->create([
            'team_id' => $team->id,
            'created_by' => $dev1->id
        ]);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $dev2->id,
            'status' => 'Todo'
        ]);

        Sanctum::actingAs($dev1);

        $response = $this->patchJson("/api/tasks/{$task->id}", [
            'status' => 'InProgress'
        ]);

        $response->assertStatus(403);
    }
}