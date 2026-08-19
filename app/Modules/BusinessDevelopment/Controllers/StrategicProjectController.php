<?php

namespace Modules\BusinessDevelopment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BusinessDevelopment\Models\StrategicProject;
use App\Modules\BusinessDevelopment\Resources\StrategicProjectResource;

class StrategicProjectController extends Controller
{
    public function index()
    {
        $strategicProjects = StrategicProject::all();
        return StrategicProjectResource::collection($strategicProjects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|max:255',
        ]);

        $strategicProject = StrategicProject::create($validated);

        return (new StrategicProjectResource($strategicProject))
            ->response();
    }

    public function show(StrategicProject $strategicProject)
    {
        return new StrategicProjectResource($strategicProject);
    }

    public function update(Request $request, StrategicProject $strategicProject)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|string|max:255',
        ]);

        $strategicProject->update($validated);

        return new StrategicProjectResource($strategicProject);
    }

    public function destroy(StrategicProject $strategicProject)
    {
        $strategicProject->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
