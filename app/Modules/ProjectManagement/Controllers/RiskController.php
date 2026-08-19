<?php

namespace Modules\ProjectManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Risk;
use App\Modules\ProjectManagement\Resources\RiskResource;

class RiskController extends Controller
{
    public function index()
    {
        $risks = Risk::with('project')->get();
        return RiskResource::collection($risks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'severity' => 'nullable|string|max:255',
            'mitigation_plan' => 'nullable|string',
        ]);

        $risk = Risk::create($validated);

        return (new RiskResource($risk->load('project')))
            ->response();
    }

    public function show(Risk $risk)
    {
        return new RiskResource($risk->load('project'));
    }

    public function update(Request $request, Risk $risk)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'title' => 'sometimes|required|string|max:255',
            'severity' => 'nullable|string|max:255',
            'mitigation_plan' => 'nullable|string',
        ]);

        $risk->update($validated);

        return new RiskResource($risk->load('project'));
    }

    public function destroy(Risk $risk)
    {
        $risk->load('project');

        $risk->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }
}
