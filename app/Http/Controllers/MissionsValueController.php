<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MissionsValue;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MissionValueResource;

class MissionsValueController extends Controller
{
    public function index()
    {
        $missionValues = MissionsValue::all();
        return MissionValueResource::collection($missionValues);
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
            $validated['image'] = $request->file('image')->store('mission-values', 'public');
        }

        $missionValue = MissionsValue::create($validated);

        return (new MissionValueResource($missionValue))
            ->response()
            ->setStatusCode(201);
    }

    public function show(MissionsValue $missionValue)
    {
        return new MissionValueResource($missionValue);
    }

    public function update(Request $request, MissionsValue $missionValue)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($missionValue->image) {
                Storage::disk('public')->delete($missionValue->image);
            }
            $validated['image'] = $request->file('image')->store('mission-values', 'public');
        }

        $missionValue->update($validated);

        return new MissionValueResource($missionValue);
    }

    public function destroy(MissionsValue $missionValue)
    {
        if ($missionValue->image) {
            Storage::disk('public')->delete($missionValue->image);
        }

        $missionValue->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
