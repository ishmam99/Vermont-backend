<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustriesServed;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\IndustriesServedResource;

class IndustriesServedController extends Controller
{
    public function index()
    {
        $industries = IndustriesServed::all();
        return IndustriesServedResource::collection($industries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|in:Aerospace & Defense,Automotive,Medical Imaging',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'status' => 'sometimes|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('industries', 'public');
        }

        $industry = IndustriesServed::create($validated);

        return (new IndustriesServedResource($industry))
            ->response()
            ->setStatusCode(201);
    }

    public function show(IndustriesServed $industriesServed)
    {
        return new IndustriesServedResource($industriesServed);
    }

    public function update(Request $request, IndustriesServed $industriesServed)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'department' => 'sometimes|required|in:Aerospace & Defense,Automotive,Medical Imaging',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'status' => 'sometimes|in:Active,Inactive',
        ]);

        if ($request->hasFile('image')) {
            if ($industriesServed->image) {
                Storage::disk('public')->delete($industriesServed->image);
            }
            $validated['image'] = $request->file('image')->store('industries', 'public');
        }

        $industriesServed->update($validated);

        return new IndustriesServedResource($industriesServed);
    }

    public function destroy(IndustriesServed $industriesServed)
    {
        if ($industriesServed->image) {
            Storage::disk('public')->delete($industriesServed->image);
        }

        $industriesServed->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
