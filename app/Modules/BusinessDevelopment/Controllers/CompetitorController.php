<?php

namespace Modules\BusinessDevelopment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BusinessDevelopment\Models\Competitor;
use App\Modules\BusinessDevelopment\Resources\CompetitorResource;

class CompetitorController extends Controller
{
    public function index()
    {
        $competitors = Competitor::all();
        return CompetitorResource::collection($competitors);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
        ]);

        $competitor = Competitor::create($validated);

        return (new CompetitorResource($competitor))
            ->response();
    }

    public function show(Competitor $competitor)
    {
        return new CompetitorResource($competitor);
    }

    public function update(Request $request, Competitor $competitor)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
        ]);

        $competitor->update($validated);

        return new CompetitorResource($competitor);
    }

    public function destroy(Competitor $competitor)
    {
        $competitor->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
