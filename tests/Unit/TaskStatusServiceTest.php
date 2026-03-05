<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Services\TaskStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class TaskStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_status_transition()
    {
        $task = Task::factory()->forProject()->create([
            'status' => 'Todo'
        ]);

        $service = new TaskStatusService();

        $service->transition($task, 'InProgress');

        $this->assertEquals('InProgress', $task->fresh()->status);
    }

    public function test_invalid_status_transition()
    {
        $this->expectException(ValidationException::class);

        $task = Task::factory()->create([
            'status' => 'Todo'
        ]);

        $service = new TaskStatusService();

        $service->transition($task, 'Done');
    }
}