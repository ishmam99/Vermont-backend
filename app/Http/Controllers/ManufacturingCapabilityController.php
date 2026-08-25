<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManufacturingCapability;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ManufacturingCapabilityResource;
use Illuminate\Validation\Rule;

class ManufacturingCapabilityController extends Controller
{
    public function index()
    {
        $capabilities = ManufacturingCapability::orderBy('sort_order', 'asc')->get();
        return ManufacturingCapabilityResource::collection($capabilities);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:manufacturing_capabilities,slug',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ]);

        // If a custom slug is explicitly provided in the request, use it
        if ($request->filled('slug')) {
            $validated['slug'] = \Illuminate\Support\Str::slug($request->slug);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('manufacturing-capabilities', 'public');
        }

        $capability = ManufacturingCapability::create($validated);

        return (new ManufacturingCapabilityResource($capability))
            ->response()
            ->setStatusCode(201);
    }

    public function show(ManufacturingCapability $manufacturingCapability)
    {
        return new ManufacturingCapabilityResource($manufacturingCapability);
    }

    public function update(Request $request, ManufacturingCapability $manufacturingCapability)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('manufacturing_capabilities', 'slug')->ignore($manufacturingCapability->id),
            ],
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ]);

        if ($request->filled('slug')) {
            $validated['slug'] = \Illuminate\Support\Str::slug($request->slug);
        }

        if ($request->hasFile('image')) {
            if ($manufacturingCapability->image) {
                Storage::disk('public')->delete($manufacturingCapability->image);
            }
            $validated['image'] = $request->file('image')->store('manufacturing-capabilities', 'public');
        }

        $manufacturingCapability->update($validated);

        return new ManufacturingCapabilityResource($manufacturingCapability);
    }

    public function destroy(ManufacturingCapability $manufacturingCapability)
    {
        if ($manufacturingCapability->image) {
            Storage::disk('public')->delete($manufacturingCapability->image);
        }

        $manufacturingCapability->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
