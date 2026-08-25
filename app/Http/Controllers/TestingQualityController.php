<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestingQuality;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TestingQualityResource;

class TestingQualityController extends Controller
{
    public function index()
    {
        $testingQualities = TestingQuality::all();
        return TestingQualityResource::collection($testingQualities);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('testing-qualities', 'public');
        }

        $testingQuality = TestingQuality::create($validated);

        return (new TestingQualityResource($testingQuality))
            ->response()
            ->setStatusCode(201);
    }

    public function show(TestingQuality $testingQuality)
    {
        return new TestingQualityResource($testingQuality);
    }

    public function update(Request $request, TestingQuality $testingQuality)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($testingQuality->image) {
                Storage::disk('public')->delete($testingQuality->image);
            }
            $validated['image'] = $request->file('image')->store('testing-qualities', 'public');
        }

        $testingQuality->update($validated);

        return new TestingQualityResource($testingQuality);
    }

    public function destroy(TestingQuality $testingQuality)
    {
        if ($testingQuality->image) {
            Storage::disk('public')->delete($testingQuality->image);
        }

        $testingQuality->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
