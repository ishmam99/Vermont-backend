<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialProcesse;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MaterialProcessResource;

class MaterialProcesseController extends Controller
{
    public function index()
    {
        $materialProcesses = MaterialProcesse::all();
        return MaterialProcessResource::collection($materialProcesses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('material-processes', 'public');
        }

        $materialProcess = MaterialProcesse::create($validated);

        return (new MaterialProcessResource($materialProcess))
            ->response()
            ->setStatusCode(201);
    }

    public function show(MaterialProcesse $materialProcess)
    {
        return new MaterialProcessResource($materialProcess);
    }

    public function update(Request $request, MaterialProcesse $materialProcess)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($materialProcess->image) {
                Storage::disk('public')->delete($materialProcess->image);
            }
            $validated['image'] = $request->file('image')->store('material-processes', 'public');
        }

        $materialProcess->update($validated);

        return new MaterialProcessResource($materialProcess);
    }

    public function destroy(MaterialProcesse $materialProcess)
    {
        if ($materialProcess->image) {
            Storage::disk('public')->delete($materialProcess->image);
        }

        $materialProcess->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
