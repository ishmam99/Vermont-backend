<?php

namespace Modules\BusinessDevelopment\Controllers;

use App\Http\Controllers\Controller;
use Modules\BusinessDevelopment\Models\Partner;
use App\Modules\BusinessDevelopment\Resources\PartnerResource;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::all();
        return PartnerResource::collection($partners);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $partner = Partner::create($validated);

        return (new PartnerResource($partner))
            ->response();
    }

    public function show(Partner $partner)
    {
        return new PartnerResource($partner);
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $partner->update($validated);

        return new PartnerResource($partner);
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return response()->json([
            'message' => 'Data deleted successfully'
        ]);
    }
}
