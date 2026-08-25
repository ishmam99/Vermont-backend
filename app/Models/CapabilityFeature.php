<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapabilityFeature extends Model
{
    protected $guarded = ['id'];

    public function manufacturingCapability()
    {
        return $this->belongsTo(ManufacturingCapability::class, 'manufacturing_capability_id');
    }
}
