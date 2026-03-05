<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\TaskStatusService;
use App\Events\TaskAssigned;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * GET /api/tasks
     * Filtering + Pagination + Eager Loading
     */
    public function index(Request $request)
    {
        $tasks = Task::with(['project', 'assignedUser'])
            ->whereHas('project', function ($query) {
                $query->where('team_id', auth()->user()->team_id);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->paginate(10);

        if(count($tasks) == 0){
            return response()->json(['message' => 'No tasks found of status:'. $request->status], 404);    
        }
        return response()->json([
            'message' => 'Tasks fetched successfully',
            'data' => $tasks
        ]);
    }

    /**
     * POST /api/tasks
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date'
        ]);

        // Ensure project belongs to same team
        $project = Project::where('team_id', auth()->user()->team_id)
            ->findOrFail($data['project_id']);

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'project_id' => $project->id,
            'assigned_to' => $data['assigned_to'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'status' => 'Todo'
        ]);

        // Fire event if assigned
        if ($task->assigned_to) {
            event(new TaskAssigned($task));
        }

        return response()->json([
            'message' => 'Task created',
            'data' => $task
        ], 201);
    }

    /**
     * PATCH /api/tasks/{task}
     */
    public function update(Request $request, Task $task, TaskStatusService $statusService)
    {
        // Team isolation check
        if ($task->project->team_id !== auth()->user()->team_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        // Authorization via policy
        $this->authorize('update', $task);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:Todo,InProgress,Done',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date'
        ]);
        
        // Handle status transition using service
        if (isset($data['status'])) {
            $statusService->transition($task, $data['status']);
            unset($data['status']);
        }

        // Detect assignment change
        if (isset($data['assigned_to']) && $data['assigned_to'] != $task->assigned_to) {
            $task->assigned_to = $data['assigned_to'];
            $task->save();

            event(new TaskAssigned($task)); // Fire event if assigned user is changed
        }

        // Update remaining fields
        $task->update($data);

        return response()->json([
            'message' => 'Task updated',
            'data' => $task
        ]);
    }
}