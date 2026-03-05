<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * GET /api/projects
     */
    public function index(Request $request)
    {
        $projects = Project::where('team_id', auth()->user()->team_id)
            ->latest()
            ->paginate(10);

        return response()->json($projects);
    }

    /**
     * POST /api/projects
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $project = Project::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'team_id' => auth()->user()->team_id,
            'created_by' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }
}