<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProgramResource;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return ProgramResource::collection($programs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|in:Flight proven,Fleet standard,Global Fleet',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

        $program = Program::create($validated);

        return (new ProgramResource($program))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Program $program)
    {
        return new ProgramResource($program);
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'sometimes|required|in:Flight proven,Fleet standard,Global Fleet',
            'status' => 'sometimes|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

        $program->update($validated);

        return new ProgramResource($program);
    }

    public function destroy(Program $program)
    {
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }

        $program->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
