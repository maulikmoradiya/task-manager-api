<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrossTeamAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_project_from_other_team()
    {
        $teamA = Team::factory()->create();
        $teamB = Team::factory()->create();

        $user = User::factory()->create([
            'team_id' => $teamA->id
        ]);

        $project = Project::factory()->create([
            'team_id' => $teamB->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(404);
    }
}