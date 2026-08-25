<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompositeCapability;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CompositeCapabilityResource;

class CompositeCapabilityController extends Controller
{
    public function index()
    {
        $capabilities = CompositeCapability::all();
        return CompositeCapabilityResource::collection($capabilities);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('composite-capabilities', 'public');
        }

        $capability = CompositeCapability::create($validated);

        return (new CompositeCapabilityResource($capability))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CompositeCapability $compositeCapability)
    {
        return new CompositeCapabilityResource($compositeCapability);
    }

    public function update(Request $request, CompositeCapability $compositeCapability)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($compositeCapability->image) {
                Storage::disk('public')->delete($compositeCapability->image);
            }
            $validated['image'] = $request->file('image')->store('composite-capabilities', 'public');
        }

        $compositeCapability->update($validated);

        return new CompositeCapabilityResource($compositeCapability);
    }

    public function destroy(CompositeCapability $compositeCapability)
    {
        if ($compositeCapability->image) {
            Storage::disk('public')->delete($compositeCapability->image);
        }

        $compositeCapability->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
