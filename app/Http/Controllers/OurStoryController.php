<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\OurStoryResource;
use Illuminate\Http\Request;
use App\Models\OurStory;

class OurStoryController extends Controller
{
    public function index()
    {
        $ourStories = OurStory::all();
        return OurStoryResource::collection($ourStories);
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
            $validated['image'] = $request->file('image')->store('our-stories', 'public');
        }

        $ourStory = OurStory::create($validated);

        return (new OurStoryResource($ourStory))
            ->response()
            ->setStatusCode(201);
    }

    public function show(OurStory $ourStory)
    {
        return new OurStoryResource($ourStory);
    }

    public function update(Request $request, OurStory $ourStory)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($ourStory->image) {
                Storage::disk('public')->delete($ourStory->image);
            }
            $validated['image'] = $request->file('image')->store('our-stories', 'public');
        }

        $ourStory->update($validated);

        return new OurStoryResource($ourStory);
    }

    public function destroy(OurStory $ourStory)
    {
        if ($ourStory->image) {
            Storage::disk('public')->delete($ourStory->image);
        }

        $ourStory->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
