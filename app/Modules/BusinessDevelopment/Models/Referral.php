<?php

namespace Modules\BusinessDevelopment\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'referred_name',
        'referred_email',
        'status',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
