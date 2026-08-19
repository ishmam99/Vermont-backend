<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
