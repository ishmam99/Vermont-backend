<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessCapability;
use App\Http\Resources\ProcessCapabilityResource;

class ProcessCapabilityController extends Controller
{
    public function index()
    {
        $processCapabilities = ProcessCapability::all();
        return ProcessCapabilityResource::collection($processCapabilities);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|integer',
        ]);

        $processCapability = ProcessCapability::create($validated);

        return (new ProcessCapabilityResource($processCapability))
            ->response()
            ->setStatusCode(201);
    }

    public function show(ProcessCapability $processCapability)
    {
        return new ProcessCapabilityResource($processCapability);
    }

    public function update(Request $request, ProcessCapability $processCapability)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|integer',
        ]);

        $processCapability->update($validated);

        return new ProcessCapabilityResource($processCapability);
    }

    public function destroy(ProcessCapability $processCapability)
    {
        $processCapability->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
