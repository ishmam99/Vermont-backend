<?php

namespace App\Modules\BusinessDevelopment\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitorResource extends JsonResource
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
            'strengths' => $this->strengths,
            'weaknesses' => $this->weaknesses,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
