<?php

namespace App\Http\Controllers;
use App\Models\Leadership;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\LeadershipResource;
use Illuminate\Http\Request;

class LeadershipController extends Controller
{
    public function index()
    {
        $leaderships = Leadership::all();
        return LeadershipResource::collection($leaderships);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_name' => 'nullable|string|max:255',
            'topics_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('leaderships', 'public');
        }

        $leadership = Leadership::create($validated);

        return (new LeadershipResource($leadership))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Leadership $leadership)
    {
        return new LeadershipResource($leadership);
    }

    public function update(Request $request, Leadership $leadership)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_name' => 'nullable|string|max:255',
            'topics_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($leadership->image) {
                Storage::disk('public')->delete($leadership->image);
            }
            $validated['image'] = $request->file('image')->store('leaderships', 'public');
        }

        $leadership->update($validated);

        return new LeadershipResource($leadership);
    }

    public function destroy(Leadership $leadership)
    {
        if ($leadership->image) {
            Storage::disk('public')->delete($leadership->image);
        }

        $leadership->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
