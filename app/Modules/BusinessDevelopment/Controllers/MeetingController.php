<?php

namespace Modules\BusinessDevelopment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BusinessDevelopment\Models\Meeting;
use App\Modules\BusinessDevelopment\Resources\MeetingResource;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with('partner')->get();
        return MeetingResource::collection($meetings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_id' => 'nullable|exists:partners,id',
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $meeting = Meeting::create($validated);

        return (new MeetingResource($meeting->load('partner')))
            ->response();
    }

    public function show(Meeting $meeting)
    {
        return new MeetingResource($meeting->load('partner'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'partner_id' => 'nullable|exists:partners,id',
            'title' => 'sometimes|required|string|max:255',
            'scheduled_at' => 'sometimes|required|date',
            'notes' => 'nullable|string',
        ]);

        $meeting->update($validated);

        return new MeetingResource($meeting->load('partner'));
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->load('partner');

        $meeting->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
            'data' => new MeetingResource($meeting), // Includes the meeting and partner data
        ]);
    }
}
