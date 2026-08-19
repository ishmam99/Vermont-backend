<?php

namespace Modules\BusinessDevelopment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BusinessDevelopment\Models\Market;
use App\Modules\BusinessDevelopment\Resources\MarketResource;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::all();
        return MarketResource::collection($markets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region_details' => 'nullable|string',
        ]);

        $market = Market::create($validated);

        return (new MarketResource($market))
            ->response();
    }

    public function show(Market $market)
    {
        return new MarketResource($market);
    }

    public function update(Request $request, Market $market)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'region_details' => 'nullable|string',
        ]);

        $market->update($validated);

        return new MarketResource($market);
    }

    public function destroy(Market $market)
    {
        $market->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
