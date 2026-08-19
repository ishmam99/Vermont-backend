<?php

namespace Modules\ProjectManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;
use App\Modules\ProjectManagement\Resources\TaskResource;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['project', 'milestone'])->get();
        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create($validated);

        return (new TaskResource($task->load(['project', 'milestone'])))
            ->response();
    }

    public function show(Task $task)
    {
        return new TaskResource($task->load(['project', 'milestone']));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return new TaskResource($task->load(['project', 'milestone']));
    }

    public function destroy(Task $task)
    {
        $task->load(['project', 'milestone']);

        $task->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }
}
