<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
