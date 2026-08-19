<?php

namespace Modules\BusinessDevelopment\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BusinessDevelopment\Models\Referral;
use App\Modules\BusinessDevelopment\Resources\ReferralResource;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::with('partner')->get();
        return ReferralResource::collection($referrals);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'referred_name' => 'required|string|max:255',
            'referred_email' => 'required|email|max:255',
            'status' => 'sometimes|string|max:255',
        ]);

        $referral = Referral::create($validated);

        return (new ReferralResource($referral->load('partner')))
            ->response();
    }

    public function show(Referral $referral)
    {
        return new ReferralResource($referral->load('partner'));
    }

    public function update(Request $request, Referral $referral)
    {
        $validated = $request->validate([
            'partner_id' => 'sometimes|required|exists:partners,id',
            'referred_name' => 'sometimes|required|string|max:255',
            'referred_email' => 'sometimes|required|email|max:255',
            'status' => 'sometimes|string|max:255',
        ]);

        $referral->update($validated);

        return new ReferralResource($referral->load('partner'));
    }

    public function destroy(Referral $referral)
    {
        $referral->load('partner');

        $referral->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
            'data' => new ReferralResource($referral),
        ]);
    }
}
