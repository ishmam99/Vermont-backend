<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CapabilityFeature;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CapabilityFeatureResource;

class CapabilityFeatureController extends Controller
{
    public function index(Request $request)
    {
        $query = CapabilityFeature::with('manufacturingCapability')->orderBy('sort_order', 'asc');

        if ($request->has('manufacturing_capability_id')) {
            $query->where('manufacturing_capability_id', $request->manufacturing_capability_id);
        }

        return CapabilityFeatureResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'manufacturing_capability_id' => 'required|exists:manufacturing_capabilities,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('capability-features', 'public');
        }

        $feature = CapabilityFeature::create($validated);
        $feature->load('manufacturingCapability');

        return (new CapabilityFeatureResource($feature))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CapabilityFeature $capabilityFeature)
    {
        $capabilityFeature->load('manufacturingCapability');
        return new CapabilityFeatureResource($capabilityFeature);
    }

    public function update(Request $request, CapabilityFeature $capabilityFeature)
    {
        $validated = $request->validate([
            'manufacturing_capability_id' => 'sometimes|required|exists:manufacturing_capabilities,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($capabilityFeature->image) {
                Storage::disk('public')->delete($capabilityFeature->image);
            }
            $validated['image'] = $request->file('image')->store('capability-features', 'public');
        }

        $capabilityFeature->update($validated);
        $capabilityFeature->load('manufacturingCapability');

        return new CapabilityFeatureResource($capabilityFeature);
    }

    public function destroy(CapabilityFeature $capabilityFeature)
    {
        if ($capabilityFeature->image) {
            Storage::disk('public')->delete($capabilityFeature->image);
        }

        $capabilityFeature->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
