<?php
namespace App\Services;

use App\Models\Task;
use Illuminate\Validation\ValidationException;

class TaskStatusService
{
    public function transition(Task $task, string $newStatus)
    {
        $allowed = [
            'Todo' => ['InProgress'],
            'InProgress' => ['Done'],
        ];

        if (!isset($allowed[$task->status]) ||
            !in_array($newStatus, $allowed[$task->status])) {
            throw ValidationException::withMessages([
                'status' => 'Invalid status transition'
            ]);
        }

        $task->status = $newStatus;
        $task->save();
    }
}
?>