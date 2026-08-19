<?php

namespace Modules\ProjectManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Milestone;
use App\Modules\ProjectManagement\Resources\MilestoneResource;

class MilestoneController extends Controller
{
    public function index()
    {
        $milestones = Milestone::with('project')->get();
        return MilestoneResource::collection($milestones);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $milestone = Milestone::create($validated);

        return (new MilestoneResource($milestone->load('project')))
            ->response();
    }

    public function show(Milestone $milestone)
    {
        return new MilestoneResource($milestone->load('project'));
    }

    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'title' => 'sometimes|required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $milestone->update($validated);

        return new MilestoneResource($milestone->load('project'));
    }

    public function destroy(Milestone $milestone)
    {
        $milestone->load('project');

        $milestone->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }
}
