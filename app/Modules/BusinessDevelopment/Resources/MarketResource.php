<?php

namespace App\Modules\BusinessDevelopment\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'region_details' => $this->region_details,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
