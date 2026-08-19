<?php

namespace Modules\BusinessDevelopment\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'title',
        'scheduled_at',
        'notes',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
