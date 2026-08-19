<?php

namespace Modules\ProjectManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\File;
use App\Modules\ProjectManagement\Resources\FileResource;

class FileController extends Controller
{
    public function index()
    {
        $files = File::with('project')->get();
        return FileResource::collection($files);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'filename' => 'required|string|max:255',
            'path' => 'nullable|string|max:255',
            'status' => 'sometimes|string|max:255',
        ]);

        $file = File::create($validated);

        return (new FileResource($file->load('project')))
            ->response();
    }

    public function show(File $file)
    {
        return new FileResource($file->load('project'));
    }

    public function update(Request $request, File $file)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'filename' => 'sometimes|required|string|max:255',
            'path' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|string|max:255',
        ]);

        $file->update($validated);

        return new FileResource($file->load('project'));
    }

    public function destroy(File $file)
    {
        $file->load('project');

        $file->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }
}
