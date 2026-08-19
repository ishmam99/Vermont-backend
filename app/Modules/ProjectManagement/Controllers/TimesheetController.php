<?php

namespace Modules\ProjectManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Timesheet;
use App\Modules\ProjectManagement\Resources\TimesheetResource;

class TimesheetController extends Controller
{
    public function index()
    {
        $timesheets = Timesheet::with(['project', 'task'])->get();
        return TimesheetResource::collection($timesheets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'hours' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $timesheet = Timesheet::create($validated);

        return (new TimesheetResource($timesheet->load(['project', 'task'])))
            ->response();
    }

    public function show(Timesheet $timesheet)
    {
        return new TimesheetResource($timesheet->load(['project', 'task']));
    }

    public function update(Request $request, Timesheet $timesheet)
    {
        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'hours' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
        ]);

        $timesheet->update($validated);

        return new TimesheetResource($timesheet->load(['project', 'task']));
    }

    public function destroy(Timesheet $timesheet)
    {
        $timesheet->load(['project', 'task']);

        $timesheet->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }
}
